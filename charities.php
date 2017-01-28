<?php
include('header.php');
?>

<div id="middle" class="col-xs-8">
    <div class="charities">
        <?php
        $id = $_GET['id'];
        $charity = getCharityById($id);
        $charityDir = 'charities/';
        ?>
        <div class="charity-title">
            <h3><?php echo $charity['title']; ?></h3>
        </div>
        <hr />
        <div class="charity-image col-xs-5">
            <img src="<?php echo $charityDir . $charity['image']; ?>" alt="<?php echo $charity['title'] ?>" class="img-responsive img-thumbnail" />
        </div>
        <div class="charity-description">
            <p><?php echo $charity['description']; ?></p>
        </div>
        <hr/>
    </div>
</div>

<?php
include('footer.php');
?>
