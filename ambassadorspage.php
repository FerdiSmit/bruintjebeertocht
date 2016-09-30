<?php
include('header.php');
?>

<div id="middle" class="col-xs-8">
    <div class="ambassador">
        <?php
        $ambassadors = getAmbassadors();
        $ambassadorDir = 'ambassadors/';

        foreach ($ambassadors as $ambassador)
        {
            ?>
            <div class="ambassador-name">
                <h3><?php echo $ambassador['ambassador']; ?></h3>
            </div>
            <hr />
            <div class="ambassador-image col-xs-5">
                <img src="<?php echo $ambassadorDir . $ambassador['image']; ?>" alt="<?php echo $ambassador['title']; ?>" class="img-responsive img-thumbnail" />
            </div>
            <div class="ambassador-description">
                <p><?php echo $ambassador['description']; ?></p>
            </div>
            <hr/>
            <?php
        }
        ?>
    </div>
</div>

<?php
include('footer.php');
?>
