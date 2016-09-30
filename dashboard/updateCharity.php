<?php
include('dashboard.php');

$result = getCharityById();

if (isset($_POST['submit']))
{
    updateCharity();
}
?>

<div id="ambassador">
    <form role="form" method="post" class="form-horizontal" enctype="multipart/form-data">
        <div class="col-xs-10">
            <div class="form-group col-xs-7">
                <label for="title">Naam ambassadeur</label>
                <input type="text" name="title" class="form-control" value="<?php echo $result['title']; ?>">
                <?php
                if (isset($titleErr))
                {
                    echo '<span class="error">' . $titleErr . '</span>';
                }
                ?>
            </div>
            <div class="form-group col-xs-7">
                <label for="charity">Foto ambassadeur</label>
                <input type="file" name="charity" class="btn btn-primary btn-file">
                <?php
                if (isset($mapErr))
                {
                    echo '<span class="error">' . $mapErr . '</span>';
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
                <label for="tinyEditor">Beschrijving ambassedeur</label>
                <textarea id="tinyEditor" name="description"><?php echo $result['description']; ?></textarea>
                <?php
                if (isset($descErr))
                {
                    echo '<span class="error">' . $descErr . '</span>';
                }
                ?>
            </div>
            <div class="form-group col-xs-7">
                <input type="submit" name="submit" class="btn btn-primary" value="Bewerken">
            </div>
        </div>
    </form>
</div>
