<?php
$result = getNewsById();

if (isset($_POST['submit']))
{
    updateNews();
}
?>

<div id="news">
    <form role="form" method="post" class="form-horizontal">
        <div class="form-group col-sm-7">
            <label for="title">Titel</label>
            <input type="text" class="form-control" name="title" id="title" placeholder="Titel..." value="<?php echo $result['title']; ?>">
            <?php
            if (isset($updateTitleErr))
            {
                echo '<span class="error">' . $updateTitleErr . '</span>';
            }
            ?>
        </div>
        <div class="form-group col-sm-7">
            <label for="summary">Korte omschrijving</label>
            <input type="text" class="form-control" name="summary" id="summary" placeholder="Korte omschrijving" value="<?php echo $result['shortDesc']; ?>">
            <?php
            if (isset($updateShortDescErr))
            {
            echo '<span class="error">' . $updateShortDescErr . '</span>';
            }
            ?>
        </div>
        <div class="form-group col-sm-7">
            <label for="newssection">Uitgebreide omschrijving</label>
            <textarea id="newssection" name="description"><?php echo $result['longDesc']; ?></textarea>
            <?php
            if (isset($updateLongDescErr))
            {
            echo '<span class="error">' . $updateLongDescErr . '</span>';
            }
            ?>
        </div>
        <div class="form-group col-sm-7">
            <input type="submit" name="submit" class="btn btn-primary" value="Opslaan">
        </div>
    </form>
</div>