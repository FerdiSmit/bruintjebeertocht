<?php
include('dashboard.php');
?>

<a href="createNews.php">Toevoegen</a>

    <div id="news">
        <div class="col-xs-10">
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
                    echo "<td><a href='updateNews.php?id=$newsID'>Bewerken</a>";
                    echo "<td><a onclick='return confirm(\"Weet u zeker dat u dit bericht wilt verwijderen?\")' href='deleteNews.php?id=$newsID'>Verwijderen</a>";
                    echo "</tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>


<?php
include('footer.php');
?>
