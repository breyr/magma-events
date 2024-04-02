<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Magma Events - Home</title>
    <?php include("./includes/head.php"); ?>
</head>

<body class="homepage-body d-flex flex-column vh-100">
    <!-- import nav bar -->
    <?php include("./includes/nav.php"); ?>
    <main class="flex-grow-1 d-flex flex-column justify-content-center align-items-center">
        <h1 class="gradient-text"><i class="fa-solid fa-fire"></i> <span class="fw-bold">Magma Events</span></h1>
        <p class="tagline">Igniting Exceptional Experiences</p>
    </main>
    <?php include('./includes/scriptImports.php'); ?>
</body>

</html>