<?php
if (isset($_POST['submit']))
{
    checkCreateNews();
}
?>

<div id="news">
    <form role="form" method="post" class="form-horizontal">
        <div class="form-group col-sm-7">
            <label for="title">Titel</label>
            <input type="text" class="form-control" name="title" id="title" placeholder="Titel..." value="<?php if (isset($_POST['title'])) { echo $_POST['title'];} ?>">
            <?php
            if (isset($titleErr))
            {
                echo '<span class="error">' . $titleErr . '</span>';
            }
            ?>
        </div>
        <div class="form-group col-sm-7">
            <label for="summary">Korte omschrijving</label>
            <input type="text" class="form-control" name="summary" id="summary" placeholder="Korte omschrijving" value="<?php if (isset($_POST['summary'])) { echo $_POST['summary']; } ?>">
            <?php
            if (isset($summaryErr))
            {
                echo '<span class="error">' . $summaryErr . '</span>';
            }
            ?>
        </div>
        <div class="form-group col-sm-7">
            <label for="startdate">Datum</label>
            <input type="text" class="form-control" name="startdate" id="datepicker" placeholder="Startdatum..." value="<?php if (isset($_POST['startdate'])) { echo $_POST['startdate']; } ?>">
            <?php
            if (isset($startDateErr))
            {
                echo '<span class="error">' . $startDateErr . '</span>';
            }
            ?>
        </div>
        <div class="form-group col-sm-7">
            <label for="tinyEditor">Uitgebreide omschrijving</label>
            <textarea id="tinyEditor" name="description"></textarea>
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
