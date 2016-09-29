<?php
include('header.php');
?>
<div id="middle" class="col-xs-8">
    <div class="news">
        <?php
        $results = getNews();

        foreach ($results as $result)
        {
            echo '<h3 class="newsheader">' . $result['title'] . '</h3>';
            if (!empty($result['last_updated']))
            {
                echo '<div class="newsdate"><p class="date">Bewerkt op: ' . $result['last_updated'] . '</p></div>';
            }
            echo '<div class="newscontent"><p class="newsdesc">' . $result['shortDesc'] . '</p></div>';
            echo '<div class="newslink"><a href="newspage.php?id=' . $result['newsID'] . '">Lees meer...</a></div>';
            echo '<hr/>';
        }
        ?>
    </div>
</div>
<?php
include('footer.php');
?>
