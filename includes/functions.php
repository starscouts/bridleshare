<?php

global $_PROFILE;
global $app;

$app = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/includes/app.json"), true);
$token = $_POST["_session"] ?? $_GET["_session"] ?? $_COOKIE['BS_SESSION_TOKEN'] ?? null;

if (isset($token)) {
    if (!(str_contains($token, "/") || trim($token) === "" || trim($token) === "." || trim($token) === "..")) {
        if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/includes/tokens/" . str_replace("/", "", $token))) {
            $_PROFILE = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/includes/tokens/" . str_replace("/", "", $token)), true);
        }
    }
}

function getUserName(): string {
    global $_PROFILE;
    return isset($_PROFILE) ? strip_tags($_PROFILE['name']) : "Anonymous";
}

function getUserID(): string {
    global $_PROFILE;
    return isset($_PROFILE) ? strip_tags($_PROFILE['id']) : "0";
}

function isLoggedIn(): bool {
    global $_PROFILE;
    return isset($_PROFILE);
}

function sizeToString(int $size): string {
    if ($size > 1024) {
        if ($size > 1024**2) {
            if ($size > 1024**3) {
                if ($size > 1024**4) {
                    return round($size / 1024**4) . " TiB";
                } else {
                    return round($size / 1024**3) . " GiB";
                }
            } else {
                return round($size / 1024**2) . " MiB";
            }
        } else {
            return round($size / 1024) . " KiB";
        }
    } else {
        return $size . " B";
    }
}

function getLimits(): array {
    global $app;

    if (!isLoggedIn()) {
        return [
            'speed' => 500*1024
        ];
    } else {
        if (in_array(getUserID(), $app["high2"])) {
            return [
                'speed' => 10*1024**2
            ];
        } elseif (in_array(getUserID(), $app["high1"])) {
            return [
                'speed' => 3*1024**2
            ];
        } else {
            return [
                'speed' => 1024**2
            ];
        }
    }
}