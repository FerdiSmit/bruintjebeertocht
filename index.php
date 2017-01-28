<?php
include('header.php');

global $paginate;
$newsDir = 'news/';
?>
<div id="middle" class="col-xs-8">
    <div class="news">
        <?php
        $rows = getNewsForPagination();

        $records_per_page = 8;
        $query = $paginate->paging($rows, $records_per_page);
        $results = $paginate->dataView($query);

        foreach ($results as $result)
        {
            ?>
            <div class="row">
                <h3 class="home-news"><?php echo $result['title']; ?></h3>
                <?php
                if (!empty($result['image']))
                {
                    ?>
                    <div class="home-image col-xs-3">
                        <img src="<?php echo $newsDir . $result['image']; ?>" alt="<?php echo $result['image']; ?>" class="img-responsive img-thumbnail" />
                    </div>
                    <?php
                }
                if (!empty($result['last_updated']))
                {
                    ?>
                    <div class="home-date">
                        <p><?php echo $result['last_updated']; ?></p>
                    </div>
                    <?php
                }
                ?>
                <div class="home-content">
                    <p><?php echo $result['shortDesc']; ?></p>
                </div>
                <div class="home-link">
                    <a href="newspage.php?id=<?php echo $result['newsID']; ?>">Lees meer...</a>
                </div>
            </div>
            <hr/>
            <?php
//            echo '<h3 class="newsheader">' . $result['title'] . '</h3>';
//            if (!empty($result['last_updated']))
//            {
//                echo '<div class="newsdate"><p class="date">Bewerkt op: ' . $result['last_updated'] . '</p></div>';
//            }
//            echo '<div class="newscontent"><p class="newsdesc">' . $result['shortDesc'] . '</p></div>';
//            echo '<div class="newslink"><a href="newspage.php?id=' . $result['newsID'] . '">Lees meer...</a></div>';
//            echo '<hr/>';
        }
        ?>
        <ul class="pagination">
            <?php $paginate->pagingLink(getNewsForPagination(), $records_per_page); ?>
        </ul>
    </div>
</div>
<?php
include('footer.php');
?>
