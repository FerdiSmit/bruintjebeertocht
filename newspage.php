<?php
include('header.php');

global $paginate;
?>
<div id="middle" class="col-xs-8">
    <div class="news">
    <?php
    if (isset($_GET['id']))
    {
        $result = getNewsById();

        echo '<h3 class="newsheader">' . $result['title'] . '</h3>';
        if (!empty($result['last_updated']))
        {
            echo '<div class="newsdate"><p class="date">Bewerkt op: ' . $result['last_updated'] . '</p></div>';
        }
        echo '<div class="newscontent"><p class="newsdesc">' . $result['longDesc'] . '</p></div>';
        echo '<div class="newslink"><a href="index.php">Terug...</a></div>';
        echo '<hr/>';
    }
    else
    {
        $rows = getNewsForPagination();

        $records_per_page = 5;
        $query = $paginate->paging($rows, $records_per_page);
        $results = $paginate->dataView($query);

        foreach ($results as $result)
        {
            echo '<h3 class="newsheader">' . $result['title'] . '</h3>';
            if (!empty($result['last_updated']))
            {
                echo 'leeg';
            }
            echo '<div class="newscontent"><p class="newsdesc">' . $result['longDesc'] . '</p></div>';
            echo '<div class="newslink"><a href="newspage.php?id=' . $result['newsID'] . '">Lees meer...</a></div>';
            echo '<hr/>';
        }
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