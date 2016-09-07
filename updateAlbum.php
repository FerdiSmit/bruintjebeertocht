<?php

$result = getAlbumById();

if (isset($_POST['submit']))
{
    updateAlbum();
}

?>

<div class="albums">
    <form role="form" method="post" class="form-horizontal">
        <div class="form-group col-sm-7">
            <label for="title">Titel</label>
            <input type="text" name="title" class="form-control" value="<?php echo $result['title']; ?>">
            <?php
            if (isset($updateTitleErr))
            {
                echo '<span class="error">' . $updateTitleErr . '</span>';
            }
            ?>
        </div>
        <div class="form-group col-sm-7">
            <label for="title">Omschrijving</label>
            <input type="text" name="description" class="form-control" value="<?php echo $result['description']; ?>">
            <?php
            if (isset($updateDescErr))
            {
                echo '<span class="error">' . $updateDescErr . '</span>';
            }
            ?>
        </div>
        <div class="form-group col-sm-7">
            <input type="submit" name="submit" class="btn btn-primary" value="Bewerken">
        </div>
    </form>
</div>
