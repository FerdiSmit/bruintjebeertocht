<?php

?>

<a href="dashboard.php?c=createNews.php">Toevoegen</a>

<div class="row">
    <div class="col-xs-12">
        <table class="table table-responsive table-condensed">
            <thead>
                <tr>
                    <th>Titel</th>
                    <th>Datum aangemaakt</th>
                    <th>Datum aangepast</th>
                    <th>Bewerken</th>
                    <th>Verwijderen</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $results = newsList();

                foreach ($results as $result)
                {
                    $newsID = $result['newsID'];

                    echo "<tr>";
                    echo "<td>" . $result['title'] . "</td>";
                    echo "<td>" . $result['created_date'] . "</td>";
                    echo "<td>" . $result['last_updated'] . "</td>";
                    echo "<td><a href='dashboard.php?u=update.php&id=$newsID'>Bewerken</a>";
                    echo "<td><a onclick='return confirm(\"Weet u zeker dat u dit bericht wilt verwijderen?\")' href='dashboard.php?d=delete.php&id=$newsID'>Verwijderen</a>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
