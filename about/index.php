<?php $title = "About"; require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"; ?>

<div class="container">
    <br><br><br><br>
    <img src="/banner.svg" alt="About Bridleshare" style="width: 600px; max-width: 100%; display: block; margin: 0 auto;">
    <h1 style="text-align: center;">Reinventing file sharing.</h1>
    <br>

    <div id="main" style="display: grid; grid-template-columns: 1fr 1fr; grid-gap: 20px;">
        <div class="card">
            <div class="card-body">
                <h3 style="text-align: center;">Privacy by design</h3>
                <p>Bridleshare is made to be secure, from the ground up. Every file you send on Bridleshare is end-to-end encrypted, meaning only you and the recipient can read them — even information about your file (name, type and size) are encrypted.</p>
                <p>A unique encryption key is generated every time you share a new file, meaning there is no way to trace a file back to you, so even your recipient can't know you are the one sending them a file.</p>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h3 style="text-align: center;">Generous limits</h3>
                <p>Most cloud storage providers will limit the size of each file you can have on their platform. Bridleshare does not have this, meaning you can send files up to 20 GiB the same way you would send smaller files without any issue.</p>
                <p>To prevent abuse, we have designed Bridleshare to gradually give you more storage capacity as you use it. You start with 50 MiB, but once you log in and get approved, you get more storage easily.</p>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h3 style="text-align: center;">Simple and stable</h3>
                <p>If you've ever used Bittorrent, or even just Google Drive, to send files to your peers, you might have noticed they are often hard to use, or do not work like you want them to because they are hard to configure.</p>
                <p>Bridleshare makes everything extremely easy: simply open Bridleshare, drag your file, send the link to your peer, and you're done. No need to login, enter a password, change settings, or wait.</p>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h3 style="text-align: center;">Use once, forget about it</h3>
                <p>Bridleshare is made to forget about you. No data about you is ever kept on the servers, not even the fact you've used Bridleshare to share files. What you share is your business, not ours.</p>
                <p>And even if you log in; once you click on "Log out", no one is able to tell you have ever been on Bridleshare before, as all data related to you is deleted the moment you decide to log out from Bridleshare.</p>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h3 style="text-align: center;">Not for profit</h3>
                <p>Bridleshare does not sell your data and does not run ads. We do not prioritize profit, we prioritize our users.</p>
                <p>At Equestria.dev, we always put user experience first. We would never ruin your experience with ads, paid subscriptions, or anything else whatsoever. We want you to be happy to use Bridleshare rather than frustrated because of ads, paid subscriptions or malicious software.</p>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h3 style="text-align: center;">Supporting pony content</h3>
                <p>Are you a pony creator sharing your art? Or a random spreading archives across the internet? Bridleshare has got you covered. We put pony content first and foremost to bring the best experience to the community that needs it most.</p>
                <p>Bridleshare is pony-first. You can get extended capacities (more storage, faster speeds) simply by contacting us and confirming you are a pony creator. It's <i>that</i> simple and it will help you a lot.</p>
            </div>
        </div>
    </div>

    <div class="card" style="margin-top: 20px; text-align: center;">
        <div class="card-body">
            <h3>Ready to start?</h3>
            <a href="/" class="btn btn-primary">Start sharing</a>
        </div>
    </div>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"; ?>