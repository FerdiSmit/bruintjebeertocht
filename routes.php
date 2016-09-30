<?php
include('header.php');
?>

<div id="middle" class="col-xs-8">
    <div class="routes">
        <?php
        $routes = getRoutes();

        $routeDir = 'routes/';

        foreach ($routes as $route)
        {
            ?>
            <div class="route-title">
                <h3 class="route-header"><?php echo $route['title']; ?></h3>
            </div>
            <hr/>
            <div class="route-image col-xs-5">
                <img src="<?php echo $routeDir . $route['map']; ?>" alt="<?php echo $route['title']; ?>" class="img-responsive img-thumbnail"/>
            </div>
            <div class="route-description">
                <p><?php echo $route['description']; ?></p>
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
