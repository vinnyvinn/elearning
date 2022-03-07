<?php
ini_set('max_execution_time', 300);
if(file_exists('../env.php')){
    header("Location: ..");
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Scholar SIMS Installer</title>
    <!-- Material design icons -->
    <link href="assets/fonts/mdi/css/materialdesignicons.min.css" rel="stylesheet">
    <!-- Bootstrap CSS -->
    <link href="assets/libs/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="assets/css/simcify.min.css" rel="stylesheet">
    <!-- Signer CSS -->
    <link href="assets/css/style.css" rel="stylesheet">
    <style>
        .auth-card {
            width:70%;
        }
    </style>
</head>
<body>


<div class="auth-page">
    <div class="auth-card card">
        <div class="auth-logo float-center center-block">
        </div>
        <hr/>