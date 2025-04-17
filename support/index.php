<?php $title = "Help and support"; require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"; ?>

<div class="container">
    <br><br><br><br>
    <h1>Help and support</h1>

    <div id="box" style="display: grid; grid-template-columns: 1fr 1fr; height: max-content; margin-top: 20px; grid-gap: 20px;">
        <div>
            <div class="card">
                <div class="card-body">
                    <h4>Report bugs</h4>
                    <p>Should you find any misbehaving in Bridleshare, you can report them to us through the Equestria.dev bug tracker. You can log in with the same account you use Bridleshare to get updates on your report.</p>
                    <a class="btn btn-primary" href="https://bugs.equestria.dev" target="_blank">Open bug tracker</a>
                </div>
            </div>
        </div>
        <div>
            <div class="card">
                <div class="card-body">
                    <h4>Get help</h4>
                    <p>If you need help using a feature of Bridleshare or Bridleshare itself, or that you have a question regarding how it works and how to install it on your own server, feel free to contact us to ask.</p>
                    <a class="btn btn-primary" href="https://equestria.dev/contact" target="_blank">Contact us</a>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"; ?>