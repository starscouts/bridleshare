window.base91 = new BaseEx.Base91();
window.keys;

class BridleshareCrypt {
    static arrayBufferToBase64(buffer) {
        let binary = '';
        let bytes = new Uint8Array(buffer);
        let len = bytes.byteLength;
        for (let i = 0; i < len; i++) {
            binary += String.fromCharCode(bytes[i]);
        }
        return window.btoa( binary );
    }

    static base64ToArrayBuffer(base64) {
        let binary_string = window.atob(base64);
        let len = binary_string.length;
        let bytes = new Uint8Array(len);
        for (let i = 0; i < len; i++) {
            bytes[i] = binary_string.charCodeAt(i);
        }
        return bytes.buffer;
    }

    static async importKey(theirKey) {
        if (!window.key) await this.generateKey();
        let myKey = window.key;

        return {
            chain: await crypto.subtle.deriveKey({"name": "ECDH", "public": (await crypto.subtle.importKey("jwk", theirKey, {name: "ECDH", namedCurve: "P-256"}, false, []))}, (await crypto.subtle.importKey("jwk", (await crypto.subtle.exportKey("jwk", myKey.privateKey)), {name: "ECDH", namedCurve: "P-256"}, false, ["deriveKey"])), {name:"AES-CTR", length: 256}, true, ["encrypt", "decrypt"]),
            privateKey: myKey.privateKey,
            publicKey: myKey.publicKey,
            exported: await crypto.subtle.exportKey("jwk", myKey.publicKey)
        }
    }

    static async generateKey() {
        let myKey = window.key = await crypto.subtle.generateKey({name:"ECDH", namedCurve: "P-256"}, true, ["deriveKey"]);

        return {
            chain: null,
            privateKey: myKey.privateKey,
            publicKey: myKey.publicKey,
            exported: await crypto.subtle.exportKey("jwk", myKey.publicKey)
        }
    }

    static async encrypt(message, chain) {
        let counter = crypto.getRandomValues(new Uint8Array(16));
        let pl = {}

        pl.counter = this.arrayBufferToBase64(counter.buffer);
        pl.payload = this.arrayBufferToBase64(await crypto.subtle.encrypt({name: "AES-CTR", counter: counter, length: 64}, chain, (new TextEncoder()).encode(message)));

        return pl;
    }

    static async encryptBuffer(buffer, chain) {
        let counter = crypto.getRandomValues(new Uint8Array(16));
        let pl = {}

        pl.counter = this.arrayBufferToBase64(counter.buffer);
        pl.payload = base91.encode(new Uint8Array(await crypto.subtle.encrypt({name: "AES-CTR", counter: counter, length: 64}, chain, buffer)), "bytes", "str");

        return pl;
    }

    static async decrypt(message, chain) {
        return window.atob(this.arrayBufferToBase64(await crypto.subtle.decrypt({name: "AES-CTR", counter: new Uint8Array(this.base64ToArrayBuffer(message.counter)), length: 64}, chain, this.base64ToArrayBuffer(message.payload))));
    }

    static async decryptBuffer(message, chain) {
        return await crypto.subtle.decrypt({name: "AES-CTR", counter: new Uint8Array(this.base64ToArrayBuffer(message.counter)), length: 64}, chain, base91.decode(message.payload, "str", "bytes"));
    }
}