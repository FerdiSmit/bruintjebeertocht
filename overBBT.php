<?php
include('header.php');

global $db;

$paginate = new Paginate($db);
?>
        <div id="middle" class="col-xs-8">
            <div class="about">
                <?php
                $result = getAbout();

                echo '<h3 class="abouttitle">' . $result['title'] . '</h3>';
                echo '<p class="aboutcontent">' . $result['about'] . '</p>';
                ?>
            </div>
        </div>
<?php
include('footer.php');
?>