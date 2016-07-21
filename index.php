<?php
include('header.php');

require('includes/functions.php');
require('includes/classes/paginate.php');

global $db;

$paginate = new Paginate($db);
?>

<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <table class="table table-striped table-condensed table-bordered table-rounded">
                <thead>
                    <tr>
                        <th>Titel</th>
                        <th>Korte beschrijving</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query = "SELECT title, shortDesc FROM news";
                    $records_per_page = 10;
                    $newQuery = $paginate->paging($query, $records_per_page);
                    $rows = $paginate->dataView($newQuery);

                    foreach ($rows as $row)
                    {
                        echo "<tr>";
                        echo "<td>" . $row['title'] . "</td>";
                        echo "<td>" . $row['shortDesc'] . "</td>";
                        echo "</tr>";
                    }

                    ?>
                </tbody>
            </table>
            <ul class="pagination">
                <?php
                $paginate->pagingLink($query, $records_per_page);
                ?>
            </ul>
        </div>
    </div>
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <?php
            $results = getPoll();

                echo '<form role="form" method="post" class="form-horizontal">';
                echo '<div class="form-group col-sm-7">';
                echo '<label for="question">' . $results['question'] . '</label><br />';
                echo '<input type="radio" name="poll" value="' . $results['answer1'] . '">' . $results['answer1'] . '<br/>';
                echo '<input type="radio" name="poll" value="' . $results['answer2'] . '">' . $results['answer2'] . '<br/>';
                echo '<input type="radio" name="poll" value="' . $results['answer3'] . '">' . $results['answer3'] . '<br/>';
            ?>
        </div>
    </div>
</div>
