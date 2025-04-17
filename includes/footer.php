<div class="container" style="margin-top: 45px;">
    <hr>

    <script>
        window.onscroll = () => {
            if (window.scrollY > 56) {
                document.getElementById("nav").classList.remove("bg-white");
                document.getElementById("nav").classList.add("bg-light");
            } else {
                document.getElementById("nav").classList.add("bg-white");
                document.getElementById("nav").classList.remove("bg-light");
            }
        }
    </script>

    <img src="https://equestria.dev/assets/brand/Wordmark/MonoLight/WordmarkMonoLight.svg" alt="Equestria.dev" style="height: 48px;">
    <span class="text-muted" style="float: right; vertical-align: middle; margin-top: 10px;">© <?= date('Y') !== "2023" ? "2023-" . date('Y') : date('Y') ?> Equestria.dev Developers</span>
</div>
<br>

</body>
</html>