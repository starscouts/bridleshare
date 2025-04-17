<?php

require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/functions.php";

if (!isLoggedIn()) {
    header("Location: /");
    die();
}

header("Location: https://account.equestria.dev/hub/users/" . getUserID());
die();