<?php

include('dashboard.php');

$results = getPictures();
$id = $_GET['id'];

$album = getAlbumByNameAndId($id);

$albumDir = '../albums/' . $album['title'] . '/';

?>

<div class="pictures">
    <div class="col-xs-12">
        <table class="table table-condensed table-responsive">
            <tbody>
            <?php
            $counter = 1;

            foreach ($results as $result)
            {
                if (!(($counter++) % 5))
                {
                    echo '<td><img class="img-responsive img-thumbnail" src="' . $albumDir . $result['picture'] . '"></td></tr><tr>';
                }
                else
                {
                    echo '<td><img class="img-responsive img-thumbnail" src="' . $albumDir .  $result['picture'] . '"></td>';
                }
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

<?php
include('footer.php');
?>