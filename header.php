<?php
require('includes/functions.php');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Bruintje Beer Tocht</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" >
    <link rel="icon" type="image/ico" href="images/bbt.ico">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <script type="text/javascript" src="js/jquery.js"></script>
    <script type="text/javascript" src="js/bootstrap.min.js"></script>
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
                <a href="index.php"><img src="images/bruintje_beer_logo.png" alt="Bruintje Beer" class="img-responsive bruintje-beer"></a>
            </div>
            <div class="title col-xs-6">
                <a href="index.php"><img src="images/bruintjebeer.png" alt="Bruintje Beer Tocht" class="img-responsive bruintjebeer"/></a>
            </div>
            <div class="col-xs-3">
                <img src="images/logo_ftc_musselkanaal.jpg" alt="FTC Musselkanaal" class="img-responsive ftcmusselkanaal"/>
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
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Sponsoren <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="sponsors2017.php">Sponsoren 2017</a></li>
                                <li><a href="sponsors.php">Sponsoren 2016</a></li>
                            </ul>
                        </li>
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
                        <?php
                        $countCharities = count(getCharities());

                        if ($countCharities != 0)
                        {
                            $charities = getCharities();
                            ?>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Goede doelen <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <?php
                                        foreach($charities as $charity)
                                        {
                                            echo '<a href="charities.php?id=' . $charity['charityID'] . '">' . $charity['title'] . '</a>';
                                        }
                                        ?>
                                    </li>
                                </ul>
                            </li>
                            <?php
                        }
                        ?>
                        <li><a href="magazine.php">Magazine</a></li>
                        <li><a href="contact.php">Contact</a></li>
                        <li>

                        </li>
                    </ul>
                    <div>
                        <a href="https://www.facebook.com/bruintjebeertocht/" target="_blank" class="pull-right facebook-icon"></a>
                    </div>
                    <div>
                        <a href="mailto:info@bruintjebeertocht.nl" target="_top" class="pull-right email-icon"></a>
                    </div>
                </div>
            </nav>
        </div>
    </div>
    <div class="row">
        <div id="slider" class="col-xs-12">
            <img src="images/bbt_1_1.png" alt="Foto BBT Tocht 2016" />
            <img src="images/bbt_1_2.png" alt="Foto BBT Tocht 2016" />
            <img src="images/bbt_1_3.png" alt="Foto BBT Tocht 2016" />
            <img src="images/bbt_1_4.png" alt="Foto BBT Tocht 2016" />
        </div>
    </div>
    <div class="row">
        <div id="flexbox">
            <div id="leftside" class="col col-xs-2">
                <div class="row">
                    <div class="ambassadors">
                        <hr/>
                        <h4 class="leftside-header">Ambassadeur 2017:</h4>
                        <hr />
                        <div class="leftside-image">
                            <a href="http://www.kjeldnuis.nl/" target="_blank">
                                <img src="images/kjeld-nuis.jpg" alt="Kjeld Nuis" class="img-responsive img-circle">
                                <p class="leftside-name">Kjeld Nuis</p>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="partners">
                        <hr/>
                        <h4 class="leftside-header">Partners:</h4>
                        <hr/>
                        <div class="leftside-image">
                            <a href="http://www.altaflex.nl" target="_blank">
                                <img src="images/logo_altaflex.jpg" alt="Altaflex" class="img-responsive img-rounded">
                                <p class="leftside-name">Altaflex</p>
                            </a>
                            <a href="http://www.ftcmusselkanaal.nl" target="_blank">
                                <img src="images/logo_ftc_musselkanaal.jpg" alt="FTC Musselkanaal" class="img-responsive img-rounded">
                                <p class="leftside-name">FTC Musselkanaal</p>
                            </a>
                            <a href="http://www.firma58.nl" target="_blank">
                                <img src="images/logo_firma58.jpg" alt="Firma 58" class="img-responsive img-rounded">
                                <p class="leftside-name">Firma 58</p>
                            </a>
                            <a href="https://www.stadskanaal.nl/" target="_blank">
                                <img src="images/logo_gemeente_stadskanaal.jpg" alt="Gemeente Stadskanaal" class="img-responsive img-rounded">
                                <p class="leftside-name">Gemeente Stadskanaal</p>
                            </a>
                            <a href="http://strijkerbuitenreklame.nl/" target="_blank">
                                <img src="images/logo_strijker.jpg" alt="Strijker Reclame" class="img-responsive img-rounded">
                                <p class="leftside-name">Strijker Reclame</p>
                            </a>
                            <a href="http://www.meijco.nl/" target="_blank">
                                <img src="images/logo-meijco.jpg" alt="Firma 58" class="img-responsive img-rounded">
                                <p class="leftside-name">Sportfotografie</p>
                            </a>
                            <a href="https://www.jens-hosting.nl/" target="_blank">
                                <img src="images/logo_jens_hosting.jpg" alt="Jens Hosting" class="img-responsive img-rounded">
                                <p class="leftside-name">Jens Hosting</p>
                            </a>
                            <a href="http://www.grinta.be" target="_blank">
                                <img src="images/grinta.jpg" alt="Grinta" class="img-responsive img-rounded">
                                <p class="leftside-name">Grinta</p>
                            </a>
                            <a href="http://www.cycleliveplus.be/" target="_blank">
                                <img src="images/logo_cyclelive.jpg" alt="Cyclelive" class="img-responsive img-rounded">
                                <p class="leftside-name">Cyclelive</p>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
