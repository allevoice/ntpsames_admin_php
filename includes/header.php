<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title><?=$title == true ? $title :'NULL';?></title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="<?=$url?>assets/img/favicon.png" rel="icon">
    <link href="<?=$url?>assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Vendor CSS Files -->
    <link href="<?=$url?>assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?=$url?>assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="<?=$url?>assets/css/style.css" rel="stylesheet">

</head>

<body>

<?php include $url."includes/menu_top.php"?>
<?php include $url."includes/menu_nav.php"?>


<main id="main" class="main">

    <div class="pagetitle mb-5">
        <h1><?=@$title_page==true ? $title_page: NULL;?></h1>
    </div><!-- End Page Title -->
