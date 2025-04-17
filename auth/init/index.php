<?php

$app = json_decode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/includes/app.json"), true);
$server = "account.equestria.dev";

header("Location: https://$server/hub/api/rest/oauth2/auth?client_id=" . $app["id"] . "&response_type=code&redirect_uri=https://bridleshare.equestria.dev/auth/callback&scope=Hub&request_credentials=default&access_type=offline");
die();
