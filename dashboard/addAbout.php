<?php

include('dashboard.php');

if (isset($_POST['submit']))
{
    checkAbout();
}

?>

<div id="about">
    <form role="form" method="post" class="form-horizontal">
        <div class="col-xs-10">
            <div class="form-group col-sm-7">
                <label for="title">Titel</label>
                <input type="text" name="title" class="form-control" placeholder="Titel...">
                <?php
                if (isset($titleErr))
                {
                    echo '<span class="error">' . $titleErr . '</span>';
                }
                ?>
            </div>
            <div class="form-group col-sm-7">
                <label for="tinyEditor">Over BBT</label>
                <textarea id="tinyEditor" name="about"></textarea>
                <?php
                if (isset($aboutErr))
                {
                    echo '<span class="error">' . $aboutErr . '</span>';
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