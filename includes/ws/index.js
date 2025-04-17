const { WebSocketServer } = require('ws');
const fs = require('fs');
const crypto = require('crypto');

const users = {};

function parseCookies (request) {
    const list = {};
    const cookieHeader = request.headers?.cookie;
    if (!cookieHeader) return list;

    cookieHeader.split(`;`).forEach(function(cookie) {
        let [ name, ...rest] = cookie.split(`=`);
        name = name?.trim();
        if (!name) return;
        const value = rest.join(`=`).trim();
        if (!value) return;
        list[name] = decodeURIComponent(value);
    });

    return list;
}

const wss = new WebSocketServer({ port: 19201 });

wss.on('connection', function connection(ws, req) {
    let privilegeList = JSON.parse(fs.readFileSync("../app.json").toString());

    let token = ws.token = parseCookies(req)['BS_SESSION_TOKEN'] ?? null;
    let user = ws.user = token && !token.includes("/") && token.startsWith("bs") && fs.existsSync("../tokens/" + token) ? JSON.parse(fs.readFileSync("../tokens/" + token).toString()) : null;
    let id = ws.id = crypto.randomBytes(96).toString("hex").match(/.{1,4}/g).map(i => parseInt(i, 16).toString(36)).join("").substring(0, 96);

    users[id] = ws;

    let limits;
    ws.key = null;
    ws.peer = null;
    ws.lastMessage = 0;

    if (user?.id && privilegeList['high2'].includes(user?.id)) {
        limits = ws.limits = {
            speed: 5*1024**2
        }
    } else if (user?.id && privilegeList['high1'].includes(user?.id)) {
        limits = ws.limits = {
            speed: 1024**2
        }
    } else if (user?.id) {
        limits = ws.limits = {
            speed: 500*1024
        }
    } else {
        limits = ws.limits = {
            speed: 100*1024
        }
    }

    ws.on('error', console.error);

    ws.on('message', function message(_data) {
        try {
            let data = JSON.parse(_data);
            if (!data.type || (data.type && data.type !== "keepalive" && data.type !== "encryptedData" && data.type !== "encrypted")) console.log(data);

            if (data.type === "encrypted" || data.type === "encryptedData") {
                if (ws.peer && data.message) {
                    let delay = new Date().getTime() - ws.lastMessage > 0 ? Math.round((JSON.stringify(data.message).length / limits.speed) * 1000) : 0;

                    setTimeout(() => {
                        ws.lastMessage = new Date().getTime();

                        ws.peer.send(JSON.stringify({
                            type: data.type,
                            chunk: data.chunk ?? null,
                            message: data.message,
                            delay
                        }));
                    }, delay);
                }
            }

            if (data.type === "keys") {
                ws.key = data.key;

                if (data.me) {
                    if (!users[data.me]) {
                        users[data.me] = ws;
                        if (ws.id) delete users[ws.id];
                    }
                }

                if (data.peer) {
                    console.log(Object.keys(users));

                    if (users[data.peer]) {
                        users[data.peer].peer = ws;
                        ws.peer = users[data.peer];

                        users[data.peer].send(JSON.stringify({
                            type: "key",
                            key: ws.key
                        }));

                        ws.send(JSON.stringify({
                            type: "key",
                            key: users[data.peer].key
                        }));
                    } else {
                        ws.send(JSON.stringify({
                            type: "error",
                            error: "NO_SUCH_CLIENT"
                        }));
                        ws.close();
                    }
                }
            }
        } catch (e) {
            console.error(e);
        }
    });

    ws.on('close', () => {
        if (ws.peer) {
            ws.peer.close();
        }

        if (ws.id) delete users[ws.id];
    })

    ws.send(JSON.stringify({
        type: 'init',
        limits,
        code: ws.id
    }));
});