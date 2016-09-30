<?php
include('dashboard.php');
?>

<a href="addRoute.php">Route Toevoegen</a>

<div id="route">
    <div class="col-xs-10">
        <table class="table table-condensed table-responsive">
            <thead>
                <tr>
                    <th>Titel</th>
                    <th>Datum aangemaakt</th>
                    <th>Datum bewerkt</th>
                    <th>Bewerken</th>
                    <th>Verwijderen</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $results = getRoutes();

            foreach ($results as $result)
            {
                $id = $result['routeID'];

                echo "<tr>";
                echo "<td>" . $result['title'] . "</td>";
                echo "<td>" . $result['added_at'] . "</td>";
                echo "<td>" . $result['updated_at'] . "</td>";
                echo "<td><a href='updateRoute.php?id=$id'>Bewerken</a>";
                echo "<td><a onclick='return confirm(\"Weet u zeker dat u dit bericht wilt verwijderen?\")' href='deleteRoute.php?id=$id'>Verwijderen</a>";
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</div>