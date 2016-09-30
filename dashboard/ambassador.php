<?php
include('dashboard.php');
?>

<a href="addAmbassador.php">Ambassadeur Toevoegen</a>

<div id="ambassador">
    <div class="col-xs-10">
        <table class="table table-condensed table-responsive">
            <thead>
            <tr>
                <th>Naam ambassadeur</th>
                <th>Datum aangemaakt</th>
                <th>Datum bewerkt</th>
                <th>Bewerken</th>
                <th>Verwijderen</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $results = getAmbassadors();

            foreach ($results as $result)
            {
                $id = $result['ambassadorID'];

                echo "<tr>";
                echo "<td>" . $result['ambassador'] . "</td>";
                echo "<td>" . $result['added_at'] . "</td>";
                echo "<td>" . $result['updated_at'] . "</td>";
                echo "<td><a href='updateAmbassador.php?id=$id'>Bewerken</a>";
                echo "<td><a onclick='return confirm(\"Weet u zeker dat u dit bericht wilt verwijderen?\")' href='deleteAmbassador.php?id=$id'>Verwijderen</a>";
                echo "</tr>";
            }
            ?>
            </tbody>
        </table>
    </div>
</div>