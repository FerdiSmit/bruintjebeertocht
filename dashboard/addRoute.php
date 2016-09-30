<?php
include('dashboard.php');

if (isset($_POST['submit']))
{
    checkRoute();
}
?>

<div id="route">
    <form role="form" method="post" class="form-horizontal" enctype="multipart/form-data">
        <div class="col-xs-10">
            <div class="form-group col-xs-7">
                <label for="title">Titel</label>
                <input type="text" name="title" class="form-control">
                <?php
                if (isset($titleErr))
                {
                    echo '<span class="error">' . $titleErr . '</span>';
                }
                ?>
            </div>
            <div class="form-group col-xs-7">
                <label for="map">Routekaart</label>
                <input type="file" name="map" class="btn btn-primary btn-file">
                <?php
                if (isset($mapErr))
                {
                    echo '<span class="error">' . $mapErr . '</span>';
                }
                elseif (isset($sizeErr))
                {
                    echo '<span class="error">' . $sizeErr . '</span>';
                }
                elseif (isset($extErr))
                {
                    echo '<span class="error">' . $extErr . '</span>';
                }
                ?>
            </div>
            <div class="form-group col-xs-7">
                <label for="tinyEditor">Beschrijving Route</label>
                <textarea id="tinyEditor" name="description"></textarea>
                <?php
                if (isset($descErr))
                {
                    echo '<span class="error">' . $descErr . '</span>';
                }
                ?>
            </div>
            <div class="form-group col-xs-7">
                <input type="submit" name="submit" class="btn btn-primary" value="Toevoegen">
            </div>
        </div>
    </form>
</div>
