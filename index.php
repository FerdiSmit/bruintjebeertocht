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
                        <li><a href="#">Over BBT</a></li>
                        <li><a href="#">Nieuws</a></li>
                        <li><a href="#">Route 2017</a></li>
                        <li><a href="#">Sponsors</a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Foto's <span class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li><a href="album2016.php">Foto's 2016</a></li>
                            </ul>
                        </li>
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

        </div>
        <div id="middle">
            <?php
            $results = getNews();

            foreach($results as $result)
            {
                echo '<div class="news">';
                echo '<h3>' . $result['title'] . '</h3>';
                echo '<p class="date">' . $result['last_updated'] . '</p>';
                echo '<p>' . $result['shortDesc'] . '</p>';
            }
            ?>
        </div>
        <div id="rightside">
                <div>
                    <?php
                    $results = getPoll();

                    if ($results !== false) {
                        echo '<form role="form" method="post" class="form-horizontal">';
                        echo '<div class="form-group col-sm-7">';
                        echo '<label for="question">' . $results['question'] . '</label><br />';
                        echo '<input type="radio" name="poll" value="' . $results['answer1'] . '">' . $results['answer1'] . '<br/>';
                        echo '<input type="radio" name="poll" value="' . $results['answer2'] . '">' . $results['answer2'] . '<br/>';
                        echo '<input type="radio" name="poll" value="' . $results['answer3'] . '">' . $results['answer3'] . '<br/>';
                    }
                    ?>
                </div>
            </div>
    </div>
<?php
include('footer.php');
?>