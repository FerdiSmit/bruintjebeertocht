<?php

if (isset($_POST['submit']))
{
    checkAlbum();
}

?>

<div class="album">
    <form role="form" method="post" class="form-horizontal">
        <div class="form-group col-sm-7">
            <label for="title">Titel</label>
            <input type="text" name="title" class="form-control">
            <?php
            if (isset($titleErr))
            {
                echo '<span class="error">' . $titleErr . '</span>';
            }
            elseif (isset($albumExistErr))
            {
                echo '<span class="error">' . $albumExistErr . '</span>';
            }
            ?>
        </div>
        <div class="form-group col-sm-7">
            <label for="description">Omschrijving</label>
            <input type="text" name="description" class="form-control">
            <?php
            if (isset($descErr))
            {
                echo '<span class="error">' . $descErr . '</span>';
            }
            ?>
        </div>
        <div class="form-group col-sm-7">
            <input type="submit" name="submit" class="btn btn-primary" value="Opslaan">
        </div>
    </form>
</div>
