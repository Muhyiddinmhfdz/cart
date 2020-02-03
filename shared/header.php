<!doctype html>
<html lang="en">


<head>
    <title>Klasifikasi Berita Naive Bayes</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" name="viewport" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

    <!-- Fonts and icons -->
    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700|Roboto+Slab:400,700|Material+Icons" />
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css">

    <!-- Material Kit CSS -->
    <link href="assets/css/material-dashboard.css?v=2.1.1" rel="stylesheet" />
    <link href="assets/demo/demo.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.9/dist/css/bootstrap-select.min.css">
    <!-- Datatable -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jszip-2.5.0/dt-1.10.18/b-1.5.6/b-colvis-1.5.6/b-flash-1.5.6/b-html5-1.5.6/b-print-1.5.6/r-2.2.2/datatables.min.css" />
    <!-- JS CORE -->
    <script src="assets/js/core/jquery.min.js"></script>
</head>

<body>
    <style>
        .btn-add {
            margin-top: -38px;
            margin-bottom: 0px
        }

        .main-panel {
            width: 100% !important;
        }

        .content {
            padding: 30px 50px !important;
            /* width: calc(100% - 260px);
            float:left;
            left:-10%; */
        }

        .body {
            background-image: url("assets/img/angular.png");
        }

        .dataTables_filter [input] {
            background: no-repeat center bottom, center calc(100% - 1px);
            background-size: 0 100%, 100% 100%;
            border: 0;
            height: 36px;
            transition: background 0s ease-out;
            padding-left: 0;
            padding-right: 0;
            border-radius: 0;
            font-size: 14px;

        }

        .avatar {
            vertical-align: right;
            width: 130px;
            height: 100px;
            border-radius: 10%;
        }

        .lalala {
            position: absolute;
            top: 5;
            left: 5px;

        }
    </style>
    <div class="wrapper ">
        <div class="main-panel">
            <a class="lalala" href="https://dinus.ac.id/">
                <img src="./assets/img/udinus.png" alt="Avatar" class="avatar">
            </a>
            <!-- Navbar -->
         <!--    <nav class="navbar navbar-expand-lg navbar-transparent navbar-absolute fixed-top ">
                <div class="container-fluid">
                    <div class="navbar-wrapper">
                        <a class="navbar-brand" href="/bayes/index.php">Klasifikasi Konten Berita Naive Bayes</a>
                    </div>

            </nav> -->
            <!-- End Navbar -->

        