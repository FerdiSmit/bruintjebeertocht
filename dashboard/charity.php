<?php
include('dashboard.php');
?>

<a href="addCharity.php">Goede doel toevoegen</a>

<div id="charity">
    <div class="col-xs-10">
        <table class="table table-condensed table-responsive">
            <thead>
            <tr>
                <th>Naam goede doel</th>
                <th>Datum aangemaakt</th>
                <th>Datum bewerkt</th>
                <th>Bewerken</th>
                <th>Verwijderen</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $results = getCharities();

            foreach ($results as $result)
            {
                $id = $result['charityID'];

                echo "<tr>";
                echo "<td>" . $result['title'] . "</td>";
                echo "<td>" . $result['added_at'] . "</td>";
                echo "<td>" . $result['updated_at'] . "</td>";
                echo "<td><a href='updateCharity.php?id=$id'>Bewerken</a>";
                echo "<td><a onclick='return confirm(\"Weet u zeker dat u dit bericht wilt verwijderen?\")' href='deleteCharity.php?id=$id'>Verwijderen</a>";
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</div>