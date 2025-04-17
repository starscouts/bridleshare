<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"; ?>

<div class="container">
    <br><br><br><br>

    <div id="box" style="display: grid;grid-template-columns: 1fr 1fr;grid-gap: 50px;background-image: url('/assets/bg.png');background-position-x: right;background-position-y: bottom;background-size: 60vw;background-repeat: no-repeat;">
        <div id="main">
            <input type="file" id="file" style="display: none;" onchange="processFile();">

            <div style="margin-top: 20px; text-align: center;" id="drop-zone" class="disabled">
                <div>
                    <img src="/assets/icons/add.svg" style="width: 64px; margin-bottom: 10px;"><br>
                    <div style="font-weight: bold; font-size: 1.2em;">Drop and drop a file</div>
                    or click at <?= sizeToString(getLimits()['speed']) ?>/s
                    <div style="margin-top: 20px;">
                        <span class="btn btn-primary">Select a file to share</span><br>
                        <div style="margin-top: 10px;">
                            <?php if (!isLoggedIn()): ?>
                                <a class="small" href="/auth/init/" id="login-cta">Sign in to share at 1 MiB/s</a>
                            <?php else: ?>
                                <a class="small" href="/plan/" id="login-cta">Contact us to share larger files</a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <script>
                document.getElementById("drop-zone").onclick = (e) => {
                    if (e.target?.id !== "login-cta") document.getElementById('file').click();
                }

                document.getElementById("drop-zone").ondragover = (ev) => {
                    console.log("File(s) in drop zone");
                    ev.preventDefault();
                }

                document.getElementById("drop-zone").ondragenter = document.getElementById("drop-zone").ondragstart = () => {
                    document.getElementById("drop-zone").classList.add("drag");
                }

                document.getElementById("drop-zone").ondragleave = document.getElementById("drop-zone").ondragend = () => {
                    document.getElementById("drop-zone").classList.remove("drag");
                }

                window.onbeforeunload = () => {
                    if (connected && canResume && !forceQuit) {
                        return "";
                    }
                }

                window.onunload = () => {
                    if (connected) {
                        try {
                            sendEncrypted({
                                type: "leave"
                            });
                        } catch (e) {}
                    }
                }

                document.getElementById("drop-zone").ondrop = (ev) => {
                    console.log("File(s) dropped");
                    ev.preventDefault();

                    if (ev.dataTransfer.items) {
                        let list = [...ev.dataTransfer.items].filter(item => item.kind === "file");

                        if (list.length > 0) {
                            window.file = list[0].getAsFile();
                        }
                    } else {
                        let list = [...ev.dataTransfer.files];

                        if (list.length > 0) {
                            window.file = list[0];
                        }
                    }

                    if (window.file) openFile();
                }
            </script>
        </div>
        <div id="share" style="display: none;">
            <h1>&nbsp;</h1>

            <div class="card" style="margin-top: 5px; margin-bottom: 1rem;">
                <div class="card-body">
                    <p>
                        <b id="file-name">File name</b> · <span id="file-size">123 kB</span><span id="share-error" style="display: none;"> · (reconnecting...)</span>
                        <a class="close-btn" href="/" style="float: right;">
                            <img alt="Cancel" style="height: 24px; width: 24px;" src="/assets/icons/cancel.svg">
                        </a>
                    </p>

                    <div class="progress" style="margin-bottom: 1rem;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-progress" style="width: 100%;" id="share-progress"></div>
                    </div>

                    <div>
                        <span id="file-status">Waiting for the other user to connect...</span>
                    </div>
                </div>
            </div>
            <hr>
            <div id="share-link">Share the following link with the other user:
                <div style="margin-top: 10px; display: grid; grid-template-columns: 2fr 1fr; grid-gap: 10px;">
                    <a id="link" style="word-break: break-all;" href="#">https://bridleshare.equestria.dev/?/{code}</a>
                    <div id="qr"></div>
                </div>
            </div>
            <script>
                document.getElementById("link").onclick = (e) => {
                    e.preventDefault();
                    return false;
                }
            </script>
            <div id="share-disclaimer" style="display: none;">The exchanged data cannot be read by anyone other than you and the other user.</div>
        </div>
        <div id="receive" style="display: none;">
            <h1>&nbsp;</h1>

            <div class="card" style="margin-top: 5px; margin-bottom: 1rem;">
                <div class="card-body">
                    <p id="receive-info" style="display: none;">
                        <b id="rfile-name">File name</b> · <span id="rfile-size">123 kB</span><span id="receive-error" style="display: none;"> · (reconnecting...)</span>
                        <a class="close-btn" href="/" style="float: right;">
                            <img alt="Cancel" style="height: 24px; width: 24px;" src="/assets/icons/cancel.svg">
                        </a>
                    </p>
                    <p id="receive-load">
                        Loading...
                        <a class="close-btn" href="/" style="float: right;">
                            <img alt="Cancel" style="height: 24px; width: 24px;" src="/assets/icons/cancel.svg">
                        </a>
                    </p>

                    <div class="progress" style="margin-bottom: 1rem;">
                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-progress" style="width: 100%;" id="receive-progress"></div>
                    </div>

                    <div>
                        <span id="rfile-status">Waiting for the other user to connect...</span>
                    </div>
                </div>
            </div>
            <hr>
            <div>The exchanged data cannot be read by anyone other than you and the other user.</div>
        </div>
        <div>
            <h1 id="title" style="margin-top: 50px;">Simple, secure file sharing</h1>
            <p style="max-width: 600px; margin-top: 20px;">Bridleshare lets you share files with end-to-end encryption and without storing it on a remote server. That way, no one (not even Equestria.dev) has access to your files, so that your private data stays private.</p>
        </div>
    </div>
</div>

<script>
    if (location.search.split("/")[1]) {
        window.fileID = location.search.split("/")[1];
        document.getElementById("main").style.display = "none";
    }

    window.onblur = () => {
        if (!window.fileID && !window.file) return;
        window.oldTitle = document.title;
        document.title = document.getElementById("rfile-status").innerText;
        window.infoInTitle = true;
    }

    window.onfocus = () => {
        if (!window.fileID && !window.file) return;
        window.infoInTitle = false;
        document.title = window.oldTitle;
    }

    window.currentID = null;
    window.maxSpeed = <?= getLimits()['speed'] ?> / 2;
    window.file = null;
    window.keyChain = null;
    window.forceQuit = false;
    window.downloadStarted = false;
    window.infoInTitle = false;
    window.oldTitle = document.title;
    window.receivedChunks = [];

    function formatSize(size, small) {
        if (size > 1024) {
            if (size > 1024**2) {
                if (size > 1024**3) {
                    if (size > 1024**4) {
                        return (size / 1024**4).toFixed(1) + (small ? "T" : " TiB");
                    } else {
                        return (size / 1024**3).toFixed(1) + (small ? "G" : " GiB");
                    }
                } else {
                    return (size / 1024**2).toFixed(1) + (small ? "M" : " MiB");
                }
            } else {
                return (size / 1024).toFixed(1) + (small ? "K" : " KiB");
            }
        } else {
            return size + " B";
        }
    }

    function openFile() {
        console.log(file);

        document.getElementById("file-name").innerText = file.name;
        document.getElementById("file-size").innerText = formatSize(file.size);

        document.title = window.oldTitle = file.name + " | " + document.title;

        document.getElementById("main").style.display = "none";
        document.getElementById("share").style.display = "";
        canResume = true;
    }

    function processFile() {
        if (document.getElementById("file").files.length > 0) {
            window.file = document.getElementById("file").files[0];
            openFile();
        }
    }

    let connected = false;
    let connecting = false;
    let canResume = false;
    let allowCrash = true;
    let totalReceived = 0;

    window.fileSize = 0;
    window.pos = 0;
    window.availableUpstreamServers = [];

    function generateToken() {
        return [...crypto.getRandomValues(new Uint8Array(128))].map(x => x.toString(16).padStart(2, '0')).join("");
    }

    setInterval(() => {
        if (connected) {
            ws.send(JSON.stringify({
                type: "keepalive"
            }));
        }
    }, 2000);


    setInterval(() => {
        for (let server of availableUpstreamServers) {
            server.send(JSON.stringify({
                type: "keepalive"
            }));
        }
    }, 5000);

    function sendEncrypted(message, cb) {
        return new Promise((res) => {
            let waiter = setInterval(async () => {
                if (keyChain.chain) {
                    clearInterval(waiter);
                    ws.send(JSON.stringify({
                        type: "encrypted",
                        message: await BridleshareCrypt.encrypt(JSON.stringify(message), keyChain.chain)
                    }), cb ?? function () {});
                    res();
                }
            });
        });
    }

    function handleEncryptedData(data, upstream) {
        let waiter = setInterval(() => {
            if (keyChain.chain) {
                clearInterval(waiter);
                BridleshareCrypt.decryptBuffer(data.message, keyChain.chain).then((ab) => {
                    window.receivedChunks[data.chunk] = ab;

                    totalReceived = totalReceived + ab.byteLength;
                    let total = window.fileSize;
                    let percentage = (totalReceived / total) * 100;

                    document.getElementById("receive-progress").className = "progress-bar bg-primary";
                    document.getElementById("receive-error").style.display = document.getElementById("share-error").style.display = "none";
                    document.getElementById("receive-progress").style.width = percentage + "%";
                    document.getElementById("rfile-status").innerText = generateStatus(false, totalReceived, total);

                    if (connected) {
                        pos++;

                        sendEncrypted({
                            type: "next",
                            chunks: [
                                {
                                    chunk: pos,
                                    upstream: upstream
                                }
                            ]
                        });
                    }
                });
            }
        });
    }

    let lastStatusUpdate = 0;
    let lastStatus;
    let lastDone = 0;
    let currentSpeed = 0;
    let speeds = [];
    let newDone;

    setInterval(() => {
        if (newDone) {
            speeds.unshift(newDone - lastDone);
            speeds = speeds.slice(0, 15);
            currentSpeed = speeds.reduce((a, b) => a + b) / speeds.length;
            lastDone = newDone;
        }
    }, 1000);

    function generateStatus(up, done, total, small) {
        newDone = done;
        let servers = availableUpstreamServers.filter(i => i._token);
        if (new Date().getTime() - lastStatusUpdate < 1000) return lastStatus;

        let eta = (1 / currentSpeed) * (total - done);
        let etaString;

        if (isFinite(eta) && !isNaN(eta)) {
            if (eta > 3600) {
                etaString = Math.round(eta / 3600) + (small ? "hr" : " hour") + (Math.round(eta / 3600) > 1 ? "s" : "");
            } else if (eta > 60) {
                etaString = Math.round(eta / 60) + (small ? "mn" : " minute") + (Math.round(eta / 60) > 1 ? "s" : "");
            } else {
                etaString = Math.round(eta) + (small ? "sec" : " second") + (Math.round(eta) > 1 ? "s" : "");
            }
        }

        let info = (up ? "↑ " : "↓ ") + formatSize(currentSpeed) + "/s · " + formatSize(done, small) + "/" + formatSize(total, small) + (etaString ? (" · " + etaString) : "");

        if (!small && servers.length > 0) {
            info = (up ? "↑ " : "↓ ") + formatSize(currentSpeed) + "/s (" + servers.length + " connection" + (servers.length > 1 ? "s" : "") + ") · " + formatSize(done, small) + "/" + formatSize(total, small) + (etaString ? (" · " + etaString) : "");
        } else if (servers.length > 0) {
            info = (up ? "↑" : "↓") + formatSize(currentSpeed, small) + "/s(" + servers.length + ") · " + formatSize(done, small) + "/" + formatSize(total, small) + (etaString ? (" · " + etaString) : "");
        } else if (small) {
            info = (up ? "↑" : "↓") + formatSize(currentSpeed, small) + "/s · " + formatSize(done, small) + "/" + formatSize(total, small) + (etaString ? (" · " + etaString) : "");
        }

        if (!small) {
            if (window.infoInTitle) {
                document.title = generateStatus(up, done, total, true);
            } else {
                document.title = window.oldTitle;
            }
        }

        lastStatusUpdate = new Date().getTime();
        lastStatus = info;
        return info;
    }

    function connect() {
        connecting = true;
        window.ws = new WebSocket("wss://bridleshare.equestria.dev/ws");

        window.ws_data1 = new WebSocket("wss://bridleshare.equestria.dev:19202");
        window.ws_data2 = new WebSocket("wss://bridleshare.equestria.dev:19203");
        window.ws_data3 = new WebSocket("wss://bridleshare.equestria.dev:19204");
        window.ws_data4 = new WebSocket("wss://bridleshare.equestria.dev:19205");
        window.ws_data5 = new WebSocket("wss://bridleshare.equestria.dev:19206");

        ws.onopen = (event) => {
            console.log(event);
        }

        ws_data1.onopen = ws_data2.onopen = ws_data3.onopen = ws_data4.onopen = ws_data5.onopen = (event) => {
            console.log(event);
            availableUpstreamServers.push(event.target);

            if (!window.fileID) {
                event.target._token = generateToken();
                event.target.send(JSON.stringify({
                    type: "token",
                    token: event.target._token
                }));
            }
        }

        ws_data1.onmessage = ws_data2.onmessage = ws_data3.onmessage = ws_data4.onmessage = ws_data5.onmessage = (event) => {
            try {
                let data = JSON.parse(event.data);
                if (data.type !== "encrypted" && data.type !== "encryptedData") console.log(data);

                if (data.type === "encryptedData") {
                    handleEncryptedData(data, event.target.url);
                }
            } catch (e) {
                console.error(e);
                console.log(event);
            }
        }

        async function nextChunk(pos, upstream) {
            let chunkSize = maxSpeed / 2;
            let chunks = Math.ceil(file.size / chunkSize);

            function readArrayBuffer(file, offset, size) {
                return new Promise((res) => {
                    let reader = new FileReader();
                    let blob = file.slice(offset, offset + size);

                    reader.onload = (event) => {
                        res(reader.result);
                    }

                    reader.readAsArrayBuffer(blob);
                });
            }

            let ab = await readArrayBuffer(file, pos * chunkSize, chunkSize);

            if (ab.byteLength > 0) {
                if (availableUpstreamServers.filter(i => i.url === upstream).length > 0) {
                    availableUpstreamServers.filter(i => i.url === upstream)[0].send(JSON.stringify({
                        type: "encryptedData",
                        chunk: pos,
                        message: await BridleshareCrypt.encryptBuffer(ab, keyChain.chain)
                    }));
                } else {
                    ws.send(JSON.stringify({
                        type: "encryptedData",
                        chunk: pos,
                        message: await BridleshareCrypt.encryptBuffer(ab, keyChain.chain)
                    }));
                }
            } else {
                sendEncrypted({
                    type: "end"
                });

                document.getElementById("file-status").innerText = document.getElementById("rfile-status").innerText = "Transfer completed successfully";
                document.getElementById("receive-progress").className = document.getElementById("share-progress").className = "progress-bar bg-success";
            }

            let done = pos * chunkSize + chunkSize;
            let total = file.size;
            if (done > total) done = total;
            let percentage = (done / total) * 100;

            document.getElementById("share-progress").className = "progress-bar bg-primary";
            document.getElementById("receive-error").style.display = document.getElementById("share-error").style.display = "none";
            document.getElementById("share-progress").style.width = percentage + "%";
            document.getElementById("file-status").innerText = generateStatus(true, done, total);
        }

        ws.onmessage = (event) => {
            try {
                let data = JSON.parse(event.data);
                if (data.type !== "encrypted" && data.type !== "encryptedData") console.log(data);

                if (data.type === "encryptedData") {
                    handleEncryptedData(data, null);
                }

                if (data.type === "encrypted") {
                    let waiter = setInterval(() => {
                        if (keyChain.chain) {
                            clearInterval(waiter);
                            BridleshareCrypt.decrypt(data.message, keyChain.chain).then((msg) => {
                                let data = JSON.parse(msg);
                                console.log(data);

                                if (data.type === "leave") {
                                    forceQuit = true;
                                    location.href = "/";
                                }

                                if (data.type === "end") {
                                    let waiter = setInterval(() => {
                                        if (receivedChunks.filter(i => i === null).length === 0) {
                                            clearInterval(waiter);
                                            document.getElementById("file-status").innerText = document.getElementById("rfile-status").innerText = "Transfer completed successfully";
                                            document.getElementById("receive-progress").className = document.getElementById("share-progress").className = "progress-bar bg-success";

                                            if (window.fileID) {
                                                sendEncrypted({
                                                    type: "end"
                                                });

                                                let blob = new Blob(receivedChunks, {
                                                    type: window.fileType
                                                });

                                                if (window.downloadStarted) return;

                                                let dl = document.createElement("a");
                                                dl.href = URL.createObjectURL(blob);
                                                dl.download = document.getElementById("rfile-name").innerText.trim();
                                                dl.click();
                                                window.downloadStarted = true;
                                            }
                                        }
                                    });
                                }

                                if (data.type === "next") {
                                    for (let chunk of data.chunks) {
                                        nextChunk(chunk.chunk, chunk.upstream ?? null);
                                    }
                                }

                                if (data.type === "upstreams") {
                                    let completed = 0;
                                    let requested = data.servers.filter(i => i.url === ws_data1.url || i.url === ws_data2.url || i.url === ws_data3.url || i.url === ws_data4.url || i.url === ws_data5.url).length;

                                    for (let server of data.servers) {
                                        if (ws_data1.url === server['url']) {
                                            let waiter = setInterval(() => {
                                                if (ws_data1.readyState === 1) {
                                                    clearInterval(waiter);
                                                    ws_data1._token = server['token'];
                                                    ws_data1.send(JSON.stringify({
                                                        type: "token",
                                                        token: server['token']
                                                    }));
                                                    completed++;
                                                }
                                            });
                                        } else if (ws_data2.url === server['url']) {
                                            let waiter = setInterval(() => {
                                                if (ws_data2.readyState === 1) {
                                                    clearInterval(waiter);
                                                    ws_data2._token = server['token'];
                                                    ws_data2.send(JSON.stringify({
                                                        type: "token",
                                                        token: server['token']
                                                    }));
                                                    completed++;
                                                }
                                            });
                                        } else if (ws_data3.url === server['url']) {
                                            let waiter = setInterval(() => {
                                                if (ws_data3.readyState === 1) {
                                                    clearInterval(waiter);
                                                    ws_data3._token = server['token'];
                                                    ws_data3.send(JSON.stringify({
                                                        type: "token",
                                                        token: server['token']
                                                    }));
                                                    completed++;
                                                }
                                            });
                                        } else if (ws_data4.url === server['url']) {
                                            let waiter = setInterval(() => {
                                                if (ws_data4.readyState === 1) {
                                                    clearInterval(waiter);
                                                    ws_data4._token = server['token'];
                                                    ws_data4.send(JSON.stringify({
                                                        type: "token",
                                                        token: server['token']
                                                    }));
                                                    completed++;
                                                }
                                            });
                                        } else if (ws_data5.url === server['url']) {
                                            let waiter = setInterval(() => {
                                                if (ws_data5.readyState === 1) {
                                                    clearInterval(waiter);
                                                    ws_data5._token = server['token'];
                                                    ws_data5.send(JSON.stringify({
                                                        type: "token",
                                                        token: server['token']
                                                    }));
                                                    completed++;
                                                }
                                            });
                                        }
                                    }

                                    if (availableUpstreamServers.length > 0) {
                                        let waiter = setInterval(() => {
                                            if (completed >= requested) {
                                                clearInterval(waiter);
                                                for (let server of availableUpstreamServers) {
                                                    sendEncrypted({
                                                        type: "next",
                                                        chunks: [
                                                            {
                                                                chunk: pos,
                                                                upstream: server.url
                                                            }
                                                        ]
                                                    });

                                                    pos++;
                                                }

                                                pos--;
                                            }
                                        });
                                    } else {
                                        sendEncrypted({
                                            type: "next",
                                            chunks: [
                                                {
                                                    chunk: pos,
                                                    upstream: null
                                                }
                                            ]
                                        });
                                    }
                                }

                                if (data.type === "metadata") {
                                    document.getElementById("receive-load").style.display = "none";
                                    document.getElementById("receive-info").style.display = "";
                                    document.getElementById("rfile-name").innerText = data.name;
                                    document.getElementById("rfile-size").innerText = formatSize(data.size);

                                    window.fileType = data.mime;
                                    window.fileSize = data.size;

                                    window.receivedChunks = Array(data.chunks).fill(null);

                                    document.getElementById("file-status").innerText = document.getElementById("rfile-status").innerText = "Waiting for transfer to start...";
                                }
                            });
                        }
                    });
                }

                if (data.type === "key") {
                    document.getElementById("file-status").innerText = document.getElementById("rfile-status").innerText = "Importing keys...";

                    BridleshareCrypt.importKey(data.key).then((ks) => {
                        window.keyChain = ks;

                        document.getElementById("share-link").style.display = "none";
                        document.getElementById("share-disclaimer").style.display = "";

                        allowCrash = false;

                        if (window.file) {
                            document.getElementById("file-status").innerText = document.getElementById("rfile-status").innerText = "Sending metadata...";

                            sendEncrypted({
                                type: "metadata",
                                name: window.file.name,
                                size: window.file.size,
                                mime: window.file.type,
                                chunks: Math.ceil(window.file.size / (maxSpeed / 2))
                            });

                            window.receivedChunks = Array(Math.ceil(window.file.size / (maxSpeed / 2))).fill(null);

                            document.getElementById("file-status").innerText = document.getElementById("rfile-status").innerText = "Sending upstream servers information...";

                            sendEncrypted({
                                type: "upstreams",
                                servers: availableUpstreamServers.filter(i => i._token).map(i => {
                                    return {
                                        url: i.url,
                                        token: i._token
                                    }
                                })
                            });

                            document.getElementById("file-status").innerText = document.getElementById("rfile-status").innerText = "Waiting for receiver to be ready...";
                        } else {
                            document.getElementById("file-status").innerText = document.getElementById("rfile-status").innerText = "Waiting for metadata...";
                        }
                    });
                }

                if ((data.type === "error" && allowCrash) || data.type === "left") {
                    window.forceQuit = true;
                    location.href = "/";
                    return;
                }

                if (data.type === "init") {
                    document.getElementById("link").innerText = document.getElementById("link").href = document.getElementById("link").innerText.replace("{code}", data.code);
                    new QRCode(document.getElementById("qr"), document.getElementById("link").href);
                    if (!window.currentID) window.currentID = data.code;

                    BridleshareCrypt.generateKey().then((key) => {
                        ws.send(JSON.stringify({
                            type: "keys",
                            key: key.exported,
                            peer: window.fileID ?? null,
                            me: window.currentID ?? null
                        }));

                        connected = true;

                        if (window.fileID) {
                            document.getElementById("file-status").innerText = document.getElementById("rfile-status").innerText = "Connecting to sender...";

                            canResume = true;
                            document.getElementById("receive").style.display = "";
                        } else {
                            document.getElementById("drop-zone").classList.remove("disabled");
                        }
                    })
                }
            } catch (e) {
                console.error(e);
                console.log(event);
            }
        }

        ws.onclose = (event) => {
            console.log(event);
            connected = false;
            connecting = false;

            if (canResume) {
                document.getElementById("receive-progress").className = document.getElementById("share-progress").className = "progress-bar bg-danger";
                document.getElementById("receive-error").style.display = document.getElementById("share-error").style.display = "";

                try { ws_data1.close(); } catch (e) {}
                try { ws_data2.close(); } catch (e) {}
                try { ws_data3.close(); } catch (e) {}
                try { ws_data4.close(); } catch (e) {}
                try { ws_data5.close(); } catch (e) {}

                setTimeout(() => {
                    connect();
                }, 1000);
            }
        }

        ws_data1.onclose = ws_data2.onclose = ws_data3.onclose = ws_data4.onclose = ws_data5.onclose = (event) => {
            console.log(event);

            if (availableUpstreamServers.includes(event.target)) {
                availableUpstreamServers = availableUpstreamServers.filter(i => i !== event.target);
            }

            if (canResume) {
                try { ws_data1.close(); } catch (e) {}
                try { ws_data2.close(); } catch (e) {}
                try { ws_data3.close(); } catch (e) {}
                try { ws_data4.close(); } catch (e) {}
                try { ws_data5.close(); } catch (e) {}
                try { ws.close(); } catch (e) {}
            }
        }

        ws.onerror = ws_data1.onerror = ws_data2.onerror = ws_data3.onerror = ws_data4.onerror = ws_data5.onerror = (event) => {
            console.log(event);
        }
    }

    connect();
</script>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"; ?>