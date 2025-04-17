<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/functions.php"; ?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= isset($title) ? $title . " | " : "" ?>Bridleshare</title>
    <link href="/assets/bootstrap.min.css" rel="stylesheet">
    <link rel="shortcut icon" href="/logo.png" type="image/png">
    <script src="/assets/bootstrap.bundle.min.js"></script>
    <script src="/assets/baseex.js"></script>
    <script src="/assets/libcrypt.js"></script>
    <script src="/assets/qrcode.js"></script>
    <style>
        @media (max-width: 900px) {
            #box {
                grid-template-columns: 1fr !important;
                grid-gap: 20px !important;
                background-image: none !important;
                height: max-content !important;
            }

            #title {
                margin-top: 0 !important;
            }
        }

        #box {
            height: calc(100vh - 236px);
        }

        .close-btn:hover {
            opacity: .5;
        }

        .close-btn:active {
            opacity: .25;
        }

        #drop-zone {
            width: 100%;
            height: calc(100vh - 256px);
            border: 2px dashed rgba(0, 0, 0, .25);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
        }

        #drag-zone.drag {
            cursor: copy !important;
        }

        #drop-zone.disabled {
            opacity: .5;
            pointer-events: none;
            user-focus: none;
            user-select: none;
        }

        .bg-progress {
            background-color: var(--bs-progress-bg) !important;
        }

        .progress {
            background-image: linear-gradient(180deg, #dce0e3 0%, #e9ecef 100%);
        }

        .btn-primary, .btn-danger, .btn-success {
            transition: opacity 200ms;
        }

        .btn-primary:hover, .btn-danger:hover, .btn-success:hover {
            opacity: .9;
        }

        .btn-primary:active, .btn-danger:active, .btn-success:active {
            opacity: .8;
        }

        .bg-primary, .btn-primary {
            background-image: linear-gradient(0deg, rgba(13,110,253,1) 0%, rgb(88, 154, 252) 100%);
        }

        .btn-danger, .bg-danger {
            background-image: linear-gradient(0deg, rgba(220,53,69,1) 0%, rgb(245, 108, 121) 100%);
        }

        .btn-success, .bg-success {
            background-image: linear-gradient(0deg, rgba(25,135,84,1) 0%, rgb(79, 161, 122) 100%);
        }

        .btn-secondary, .bg-secondary {
            background-image: linear-gradient(0deg, #6c757d 0%, #969696 100%);
        }

        #nav {
            border-bottom-style: solid;
            border-bottom-width: 1px;
            border-bottom-color: transparent;
        }

        #nav.bg-light {
            border-bottom-color: rgba(0, 0, 0, .05);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-white fixed-top" style="transition: background-color 200ms, border-bottom-color 200ms;" id="nav">
        <div class="container-fluid">
            <a class="navbar-brand" href="/">
                <img src="/banner.svg" alt="Bridleshare" style="position: absolute;height: 48px;margin-top: -24px;">
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#collapsibleNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="collapsibleNavbar" style="justify-content: right;">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link<?= explode("?", $_SERVER['REQUEST_URI'])[0] === "/" ? " active" : "" ?>" href="/">Share files</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link<?= explode("?", $_SERVER['REQUEST_URI'])[0] === "/about/" ? " active" : "" ?>" href="/about/">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link<?= explode("?", $_SERVER['REQUEST_URI'])[0] === "/plans/" ? " active" : "" ?>" href="/plans/">Sharing plans</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link<?= explode("?", $_SERVER['REQUEST_URI'])[0] === "/legal/" ? " active" : "" ?>" href="/legal/">Legal notices</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link<?= explode("?", $_SERVER['REQUEST_URI'])[0] === "/support/" ? " active" : "" ?>" href="/support/">Help and support</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" href="#">|</a>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"><?= getUserName(); ?></a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <?php if (isLoggedIn()): ?>
                            <li><a class="dropdown-item" href="/account">My account</a></li>
                            <li><a class="dropdown-item" href="/logout">Log out</a></li>
                            <?php else: ?>
                            <li><a class="dropdown-item" href="/auth/init">Log in</a></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>