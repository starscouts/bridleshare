const { WebSocketServer } = require('ws');
const fs = require('fs');
const crypto = require('crypto');

const tokens = {};

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

function connectionHandler(ws, req, port) {
    let privilegeList = JSON.parse(fs.readFileSync("../app.json").toString());

    let token = ws.token = parseCookies(req)['BS_SESSION_TOKEN'] ?? null;
    let user = ws.user = token && !token.includes("/") && token.startsWith("bs") && fs.existsSync("../tokens/" + token) ? JSON.parse(fs.readFileSync("../tokens/" + token).toString()) : null;

    let limits;

    ws.token = null;
    ws.peer = null;
    ws.lastMessage = 0;

    if (user?.id && privilegeList['high2'].includes(user?.id)) {
        limits = ws.limits = {
            speed: 2*1024**2
        }
    } else if (user?.id && privilegeList['high1'].includes(user?.id)) {
        limits = ws.limits = {
            speed: 1024**2
        }
    } else if (user?.id) {
        limits = ws.limits = {
            speed: 500*1024
        }
    }

    ws.on('error', console.error);

    ws.on('message', function message(_data) {
        try {
            let data = JSON.parse(_data);
            if (!data.type || (data.type && data.type !== "keepalive" && data.type !== "encryptedData" && data.type !== "encrypted")) console.log(data);

            if (data.type === "encryptedData") {
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

            if (data.type === "token") {
                if (tokens[data.token]) {
                    tokens[data.token][1] = ws;
                    ws.peer = tokens[data.token][0];
                    ws.token = data.token;
                    tokens[data.token][0].peer = ws;
                } else {
                    if (!user?.id || (port > 19204 && !(user?.id && privilegeList['high2'].includes(user?.id))) || (port > 19203 && !(user?.id && privilegeList['high1'].includes(user?.id)) && !(user?.id && privilegeList['high2'].includes(user?.id)))) ws.close();
                    tokens[data.token] = [ ws, null ];
                }
            }
        } catch (e) {
            console.error(e);
        }
    });

    ws.on('close', () => {
        if (ws.peer) {
            ws.peer.close();
            if (ws.token) delete tokens[ws.token];
        }
    })

    ws.send(JSON.stringify({
        type: 'init',
        limits,
        code: ws.id
    }));
}

const { createServer } = require('https');

function startServer(port) {
    const server = createServer({
        cert: fs.readFileSync('/etc/letsencrypt/live/minteck.org/fullchain.pem'),
        key: fs.readFileSync('/etc/letsencrypt/live/minteck.org/privkey.pem')
    });

    const wss = new WebSocketServer({ server });
    wss.on('connection', (ws, req) => {
        connectionHandler(ws, req, port);
    });

    server.listen(port);
}

startServer(parseInt(process.argv[2]));