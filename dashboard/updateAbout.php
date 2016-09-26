<?php

include('dashboard.php');

$result = getAboutById();

if (isset($_POST['submit']))
{
    updateAbout();
}

?>

<div id="about">
    <form role="form" method="post" class="form-horizontal">
        <div class="col-xs-10">
            <div class="form-group col-sm-7">
                <label for="title">Titel</label>
                <input type="text" name="title" class="form-control" placeholder="Titel..." value="<?php echo $result['title']; ?>">
                <?php
                if (isset($updateTitleErr))
                {
                    echo '<span class="error">' . $updateTitleErr . '</span>';
                }
                ?>
            </div>
            <div class="form-group col-sm-7">
                <label for="tinyEditor">Over BBT</label>
                <textarea id="tinyEditor" name="about"><?php echo $result['about']; ?></textarea>
                <?php
                if (isset($updateAboutErr))
                {
                    echo '<span class="error">' . $updateAboutErr . '</span>';
                }
                ?>
            </div>
            <div class="form-group col-sm-7">
                <input type="submit" name="submit" class="btn btn-primary" value="Opslaan">
            </div>
        </div>
    </form>
</div>

<?php
include('footer.php');
?>