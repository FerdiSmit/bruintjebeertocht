<?php
if (isset($_POST['submit']))
{
    checkNews();
}
?>

<div id="news">
    <form role="form" method="post" class="form-horizontal">
        <div class="form-group col-sm-7">
            <label for="title">Titel</label>
            <input type="text" class="form-control" name="title" id="title" placeholder="Titel..." value="<?php if (isset($_POST['title'])) { echo $_POST['title'];} ?>">
        </div>
        <div class="form-group col-sm-7">
            <label for="summary">Korte omschrijving</label>
            <input type="text" class="form-control" name="summary" id="summary" placeholder="Korte omschrijving" value="<?php if (isset($_POST['summary'])) { echo $_POST['summary']; } ?>">
        </div>
        <div class="form-group col-sm-7">
            <label for="startdate">Startdatum</label>
            <input type="text" class="form-control datepicker" name="startdate" id="startdate" placeholder="Startdatum..." value="<?php if (isset($_POST['startdate'])) { echo $_POST['enddate']; } ?>">
        </div>
        <div class="form-group col-sm-7">
            <label for="enddate">Einddatum</label>
            <input type="text" class="form-control datepicker" name="enddate" id="startdate" placeholder="Einddatum..." value="<?php if (isset($_POST['enddate'])) { echo $_POST['startdate']; } ?>">
        </div>
        <div class="form-group col-sm-7">
            <label for="newssection">Uitgebreide omschrijving</label>
            <textarea id="newssection"></textarea>
        </div>
        <div class="form-group col-sm-7">
            <input type="submit" class="btn btn-primary" value="Opslaan">
       </div>
    </form>
</div>
