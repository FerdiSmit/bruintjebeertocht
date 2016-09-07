<?php
include('header.php');

require('includes/functions.php');
require('includes/classes/paginate.php');

global $db;

$paginate = new Paginate($db);
?>

    <div class="row">
        <div id="header col-xs-12">
            <div class="logo col-xs-3">
                <img src="images/bruintje_beer_logo.png" alt="Bruintje Beer" class="img-responsive bruintje-beer">
            </div>
            <div class="title col-xs-9">
                <h1>Bruintje Beer Tocht</h1>
            </div>
        </div>
    </div>

    <div class="row">
        <div id="menu">
            <nav class="navbar">
                <div class="container-fluid">
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
                            <li><a href="#">Route 2017</a></li>
                            <li><a href="#">Sponsors</a></li>
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
                            <li><a href="#">Contact</a></li>
                        </ul>
                    </div>
                </div>
            </nav>
        </div>
    </div>

    <div class="row">
        <div id="slider">
            <img src="images/bbt_1_1.jpg">
            <img src="images/bbt_1_2.jpg">
            <img src="images/bbt_1_3.jpg">
            <img src="images/bbt_1_4.jpg">
        </div>
    </div>

    <div id="content">
        <div id="leftside">
            Test
        </div>
        <div id="middle">
            <div class="news">
                <div class="pictures">
                    <?php
                    $id = $_GET['id'];
                    $album = getAlbumByNameAndId($id);
                    $pictures = getPictures();
                    $counter = 1;
                    $albumDir = 'albums/' . $album['title'] .'/';

                    foreach ($pictures as $picture)
                    {
                        if (!(($counter++) % 5))
                        {
                            echo '<td><img class="img-responsive img-thumbnail" src="' . $albumDir . $picture['picture'] . '"></td></tr><tr>';
                        }
                        else
                        {
                            echo '<td><img class="img-responsive img-thumbnail" src="' . $albumDir .  $picture['picture'] . '"></td>';
                        }
                    }
                    ?>
                </div>
            </div>
        </div>
        <div id="rightside">
            Test
        </div>
    </div>
<?php
include('footer.php');
?>