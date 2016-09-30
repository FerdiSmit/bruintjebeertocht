<?php
require('includes/functions.php');
require('includes/classes/paginate.php');
?>

<!DOCTYPE html>
<html>
<head>
    <title><?php if (isset($title)) { echo $title; }?></title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="css/slick.css">
    <link rel="stylesheet" type="text/css" href="css/slick-theme.css">
    <link href='https://fonts.googleapis.com/css?family=Architects+Daughter' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="css/ui-darkness.css" id="theme">
    <link rel="stylesheet" href="css/blueimp.min.css">
    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel="stylesheet" type="text/css" href="css/content.css">

    <script type="text/javascript" src="js/tinymce/js/tinymce/tinymce.min.js"></script>
</head>
<body>
<div class="container">
    <div class="row">
        <div id="header" class="col-xs-12">
            <div class="col-xs-3">
                <img src="images/bruintje_beer_logo.png" alt="Bruintje Beer" class="img-responsive bruintje-beer">
            </div>
            <div class="title col-xs-6">
                <h1>Bruintje Beer Tocht</h1>
            </div>
            <div class="col-xs-3">
                <img src="images/logo_ftc_musselkanaal.jpg" alt="FTC Musselkanaal" class="img-responsive ftcmusselkanaal"
            </div>
        </div>
    </div>
    <div class="row">
        <div id="menu" class="col-xs-12">
            <nav class="navbar">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bbt-menu" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse" id="bbt-menu">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="index.php">Home</a></li>
                        <li><a href="overBBT.php">Over BBT</a></li>
                        <li><a href="newspage.php">Nieuws</a></li>
                        <li><a href="routes.php">Route 2017</a></li>
                        <li><a href="sponsors.php">Sponsoren</a></li>
                        <li><a href="ambassadorspage.php">Ambassadeurs</a></li>
                        <?php
                        $countAlbums = count(getAlbums());

                        if ($countAlbums != 0)
                        {
                            $albums = getAlbums();
                            ?>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Foto's <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <?php
                                        foreach ($albums as $album)
                                        {
                                            echo '<a href="albums.php?id=' . $album['albumID'] . '">' . $album['title'] . '</a>';
                                        }
                                        ?>
                                    </li>
                                </ul>
                            </li>
                            <?php
                        }
                        ?>
                        <li><a href="charities.php">Goede Doelen</a></li>
                        <li><a href="magazine.php">Magazine</a></li>
                        <li><a href="contact.php">Contact</a></li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>
    <div class="row">
        <div id="slider" class="col-xs-12">
            <img src="images/bbt_1_1.jpg" alt="Foto BBT Tocht 2016" />
            <img src="images/bbt_1_2.jpg" alt="Foto BBT Tocht 2016" />
            <img src="images/bbt_1_3.jpg" alt="Foto BBT Tocht 2016" />
            <img src="images/bbt_1_4.jpg" alt="Foto BBT Tocht 2016" />
        </div>
    </div>
    <div class="row">
        <div id="flexbox">
            <div id="leftside" class="col col-xs-2">
                <div class="row">
                    <div class="ambassadors">
                        <hr/>
                        <h5 class="leftside-header">Ambassadeurs:</h5>
                        <hr />
                        <div class="leftside-image">
                            <img src="images/kjeld-nuis.jpg" alt="Kjeld Nuis" class="img-responsive img-circle">
                            <p class="leftside-name">Kjeld Nuis</p>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="partners">
                        <hr/>
                        <h5 class="leftside-header">Partners:</h5>
                        <hr/>
                        <div class="leftside-image">
                            <a href="http://www.grinta.be" target="_blank">
                                <img src="images/grinta.jpg" alt="Grinta" class="img-responsive img-rounded">
                                <p class="leftside-name">Grinta</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
