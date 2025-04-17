<?php

if (isset($_COOKIE['BS_SESSION_TOKEN'])) {
    if (file_exists($_SERVER['DOCUMENT_ROOT'] . "/includes/tokens/" . str_replace(".", "", str_replace("/", "", $_COOKIE['BS_SESSION_TOKEN'])))) {
        unlink($_SERVER['DOCUMENT_ROOT'] . "/includes/tokens/" . str_replace(".", "", str_replace("/", "", $_COOKIE['BS_SESSION_TOKEN'])));
    }

    header("Set-Cookie: BS_SESSION_TOKEN=; SameSite=None; Path=/; Secure; HttpOnly; Expires=0");
}

header("Location: /") and die();