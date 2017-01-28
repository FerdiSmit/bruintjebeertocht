<?php
include('dashboard.php');

if (isset($_POST['submit']))
{
    checkAmbassador();
}
?>

<div id="ambassador">
    <form role="form" method="post" class="form-horizontal" enctype="multipart/form-data">
        <div class="col-xs-10">
            <div class="form-group col-xs-7">
                <label for="title">Naam ambassadeur</label>
                <input type="text" name="name" class="form-control">
                <?php
                if (isset($nameErr))
                {
                    echo '<span class="error">' . $nameErr . '</span>';
                }
                ?>
            </div>
            <div class="form-group col-xs-7">
                <label for="ambassador">Foto ambassadeur</label>
                <input type="file" name="ambassador" class="btn btn-primary btn-file">
                <?php
                if (isset($ambassadorErr))
                {
                    echo '<span class="error">' . $ambassadorErr . '</span>';
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