<?php $title = "Sharing plans"; require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"; global $app; ?>

<style>
    @font-face {
        src: url("https://git.equestria.dev/equestria.dev/maretimesans/raw/branch/mane/export.otf");
        font-family: "MLPMaretimeSans";
        font-style: normal;
        font-weight: normal;
    }
</style>

<div class="container">
    <br><br><br><br>
    <h1 style="text-align: center; margin-bottom: 20px;">There are many ways you can use Bridleshare</h1>
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); grid-gap: 20px;">
        <div class="card">
            <div class="card-body">
                <img src="/assets/plans/infant.png" style="height: 128px; display: block; margin: 0 auto;" alt="">
                <h3 style="text-align: center; font-family: 'MLPMaretimeSans', sans-serif; font-size: 64px; line-height: 36px; background: linear-gradient(0deg, rgb(134 30 147) 0%, rgb(255 155 108) 100%); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;">Infant</h3>
                <div style="height: 38px;">
                    <div style="text-align: center; height: 38px; display: flex; align-items: center; justify-content: center;">You already have access</div>
                </div>

                <div style="margin: 10px 0;">
                    <div style="margin-bottom: 5px;">
                        <b>Upload speed</b>
                    </div>
                    <div class="progress" style="margin-bottom: 5px;">
                        <div class="progress-bar bg-primary" style="width: 5%;"></div>
                    </div>
                    <div>
                        500 KiB/s
                    </div>
                </div>

                <div style="margin: 10px 0;">
                    <div style="margin-bottom: 5px;">
                        <b>Parallel connections</b>
                    </div>
                    <div class="progress" style="margin-bottom: 5px;">
                        <div class="progress-bar bg-primary" style="width: 20%;"></div>
                    </div>
                    <div>
                        1 connection
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <img src="/assets/plans/filly.png" style="height: 128px; display: block; margin: 0 auto;" alt="">
                <h3 style="text-align: center; font-family: 'MLPMaretimeSans', sans-serif; font-size: 64px; line-height: 36px; background: linear-gradient(180deg, rgb(52 207 169) 0%, rgb(203 191 248) 100%); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;">FIlly</h3>
                <div style="height: 38px;">
                    <?php if (isLoggedIn()): ?>
                        <div style="text-align: center; height: 38px; display: flex; align-items: center; justify-content: center;">You already have access</div>
                    <?php else: ?>
                        <a href="/auth/init/" class="btn btn-primary" style="display: block; margin-left: auto; margin-right: auto; width: max-content;">Log in and get access</a>
                    <?php endif; ?>
                </div>

                <div style="margin: 10px 0;">
                    <div style="margin-bottom: 5px;">
                        <b>Upload speed</b>
                    </div>
                    <div class="progress" style="margin-bottom: 5px;">
                        <div class="progress-bar bg-primary" style="width: 10%;"></div>
                    </div>
                    <div>
                        1 MiB/s
                    </div>
                </div>

                <div style="margin: 10px 0;">
                    <div style="margin-bottom: 5px;">
                        <b>Parallel connections</b>
                    </div>
                    <div class="progress" style="margin-bottom: 5px;">
                        <div class="progress-bar bg-primary" style="width: 40%;"></div>
                    </div>
                    <div>
                        2 connections at 500 KiB/s each
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <img src="/assets/plans/mare.png" style="height: 128px; display: block; margin: 0 auto;" alt="">
                <h3 style="text-align: center; font-family: 'MLPMaretimeSans', sans-serif; font-size: 64px; line-height: 36px; background: linear-gradient(0deg, rgb(94 6 146) 0%, rgb(121 195 246) 100%); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;">MaRE</h3>
                <div style="height: 38px;">
                    <?php if (in_array(getUserID(), $app["high1"]) || in_array(getUserID(), $app["high2"])): ?>
                        <div style="text-align: center; height: 38px; display: flex; align-items: center; justify-content: center;">You already have access</div>
                    <?php else: ?>
                        <a href="https://equestria.dev/contact" target="_blank" class="btn btn-primary" style="display: block; margin-left: auto; margin-right: auto; width: max-content;">Contact us</a>
                    <?php endif; ?>
                </div>

                <div style="margin: 10px 0;">
                    <div style="margin-bottom: 5px;">
                        <b>Upload speed</b>
                    </div>
                    <div class="progress" style="margin-bottom: 5px;">
                        <div class="progress-bar bg-primary" style="width: 30%;"></div>
                    </div>
                    <div>
                        3 MiB/s
                    </div>
                </div>

                <div style="margin: 10px 0;">
                    <div style="margin-bottom: 5px;">
                        <b>Parallel connections</b>
                    </div>
                    <div class="progress" style="margin-bottom: 5px;">
                        <div class="progress-bar bg-primary" style="width: 60%;"></div>
                    </div>
                    <div>
                        3 connections at 1 MiB/s each
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <img src="/assets/plans/princess.png" style="height: 128px; display: block; margin: 0 auto;" alt="">
                <h3 style="text-align: center; font-family: 'MLPMaretimeSans', sans-serif; font-size: 64px; line-height: 36px; background: linear-gradient(0deg, rgb(255 155 108) 0%, rgb(255 232 192) 100%); -webkit-background-clip: text; background-clip: text; -webkit-text-fill-color: transparent;">PRINCESS</h3>
                <div style="height: 38px;">
                    <?php if (in_array(getUserID(), $app["high2"])): ?>
                        <div style="text-align: center; height: 38px; display: flex; align-items: center; justify-content: center;">You already have access</div>
                    <?php else: ?>
                        <a href="https://equestria.dev/contact" target="_blank" class="btn btn-primary" style="display: block; margin-left: auto; margin-right: auto; width: max-content;">Contact us</a>
                    <?php endif; ?>
                </div>

                <div style="margin: 10px 0;">
                    <div style="margin-bottom: 5px;">
                        <b>Upload speed</b>
                    </div>
                    <div class="progress" style="margin-bottom: 5px;">
                        <div class="progress-bar bg-primary" style="width: 100%;"></div>
                    </div>
                    <div>
                        10 MiB/s
                    </div>
                </div>

                <div style="margin: 10px 0;">
                    <div style="margin-bottom: 5px;">
                        <b>Parallel connections</b>
                    </div>
                    <div class="progress" style="margin-bottom: 5px;">
                        <div class="progress-bar bg-primary" style="width: 100%;"></div>
                    </div>
                    <div>
                        5 connections at 2 MiB/s each
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="small text-muted" style="margin-top: 20px;">The actual upload speed depends on various criteria (server load, available parallel connections, network speed, ...) and will almost never be the announced speed. Parallel connections only work on supported browsers, and might not be available entirely or at all at any point in time.</div>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"; ?>