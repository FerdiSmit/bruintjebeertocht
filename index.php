<?php
include('header.php');

require('includes/functions.php');
require('includes/classes/paginate.php');

$paginate = new Paginate($db);
?>

<div class="container">
    <div class="col-md-10 col-md-offset-1">
        <table class="table table-striped table-condensed table-bordered table-rounded">
            <thead>
                <tr>
                    <th>Titel</th>
                    <th>Korte beschrijving</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <?php
                    $query = "SELECT title, shortDesc FROM news";
                    $records_per_page = 10;
                    $newQuery = $paginate->paging($query, $records_per_page);
                    $paginate->dataView($newQuery);
                    $paginate->pagingLink($query, $records_per_page);
                    ?>
                </tr>
            </tbody>
        </table>
    </div>
</div>
