<?php
include('header.php');

global $paginate;
$newsDir = 'news/';
?>
<div id="middle" class="col-xs-8">
    <div class="news">
        <?php
        if (isset($_GET['id']))
        {
            $result = getNewsById();

            ?>
            <h3 class="news-title"><?php echo $result['title']; ?></h3>
            <?php
            if (!empty($result['image']))
            {
                ?>
                <div class="news-image col-xs-5">
                    <img src="<?php echo $newsDir . $result['image']; ?>" alt="<?php echo $result['image'] ?>" class="img-responsive img-thumbnail" />
                </div>
                <?php
            }
            if (!empty($result['last_updated']))
            {
                ?>
                <div class="news-date">
                    <p><?php echo $result['last_updated']; ?></p>
                </div>
                <?php
            }
            ?>
            <div class="news-description">
                <p><?php echo $result['longDesc']; ?></p>
            </div>
            <div class="news-link">
                <a href="index.php">Terug...</a>
            </div>
            <?php
        }
        else
        {
            $rows = getNewsForPagination();
            $records_per_page = 5;
            $query = $paginate->paging($rows, $records_per_page);
            $results = $paginate->dataView(($query));

            foreach ($results as $result)
            {
                ?>
                <div class="row">
                    <h3 class="news-title"><?php echo $result['title']; ?></h3>
                    <?php
                    if (!empty($result['image'])) {
                        ?>
                            <div class="news-image col-xs-6">
                                <img src="<?php echo $newsDir . $result['image']; ?>" alt="<?php echo $result['image'] ?>" class="img-responsive img-thumbnail"/>
                            </div>
                        <?php
                    }
                    if (!empty($result['last_updated'])) {
                        ?>
                        <div class="news-date">
                            <p>Bewerkt op: <?php echo $result['last_updated']; ?></p>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="news-description">
                        <p><?php echo $result['longDesc']; ?></p>
                    </div>

                </div>
                <hr/>
                <?php
            }
            ?>

            <ul class="pagination">
                <?php
                $paginate->pagingLink(getNewsForPagination(), $records_per_page);
                ?>
            </ul>

            <?php
        }
        ?>
    </div>
</div>
<?php
include('footer.php');
?>