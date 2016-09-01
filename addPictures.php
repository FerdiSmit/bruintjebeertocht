<?php

if (isset($_POST['submit']))
{
    checkPictures();
}

?>

<div class="pictures">
    <form role="form" method="post" class="form-horizontal" enctype="multipart/form-data">
        <div class="form-group col-sm-7">
            <label for="picture">Foto's toevoegen</label>
            <input type="file" name="picture[]" class="btn btn-primary btn-file" multiple>
            <?php
            if (isset($pictureErr))
            {
                echo '<span class="error">' . $pictureErr . '</span>';
            }
            elseif (isset($typeErr))
            {
                echo '<span class="error">' . $typeErr . '</span>';
            }
            elseif (isset($sizeErr))
            {
                echo '<span class="error">' . $sizeErr . '</span>';
            }
            ?>
        </div>
        <div class="form-group col-sm-7">
            <input type="submit" name="submit" class="btn btn-primary" value="Uploaden">
        </div>
    </form>
</div>
