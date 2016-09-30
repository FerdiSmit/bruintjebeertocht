<?php
include('dashboard.php');

if (isset($_POST['submit']))
{
    checkCharity();
}
?>

<div id="ambassador">
    <form role="form" method="post" class="form-horizontal" enctype="multipart/form-data">
        <div class="col-xs-10">
            <div class="form-group col-xs-7">
                <label for="title">Naam goede doel</label>
                <input type="text" name="title" class="form-control">
                <?php
                if (isset($titleErr))
                {
                    echo '<span class="error">' . $titleErr . '</span>';
                }
                ?>
            </div>
            <div class="form-group col-xs-7">
                <label for="charity">Logo goede doel</label>
                <input type="file" name="charity" class="btn btn-primary btn-file">
                <?php
                if (isset($charityErr))
                {
                    echo '<span class="error">' . $charityErr . '</span>';
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
                <label for="tinyEditor">Beschrijving goede doel</label>
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
