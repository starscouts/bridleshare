<?php $title = "UI testing page"; require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/header.php"; ?>

<div class="container">
    <br><br><br><br>
    <h1>UI testing page</h1>

    <div class="alert alert-warning">This is a UI testing page, none of the actions here actually work. <a href="/">Go home.</a></div>

    <div class="progress">
        <div class="progress-bar" style="width:20%"></div>
    </div>

    <br>

    <div class="progress">
        <div class="progress-bar bg-danger" style="width:50%"></div>
    </div>

    <br>

    <div class="progress">
        <div class="progress-bar bg-success" style="width:100%"></div>
    </div>

    <br>

    <p><a class="btn btn-secondary">Click me</a></p>
</div>

<?php require_once $_SERVER['DOCUMENT_ROOT'] . "/includes/footer.php"; ?>