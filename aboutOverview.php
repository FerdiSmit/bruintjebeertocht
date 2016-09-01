
<?php
$result = getAbout();

if ($result === false)
{
    echo '<a href="dashboard.php?aa=addAbout.php">Toevoegen</a>';
}
?>

<div class="row">
    <div class="col-xs-12">
        <table class="table table-responsive table-condensed">
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
                $aboutID = $result['aboutID'];

                echo "<tr>";
                echo "<td>" . $result['title'] . "</td>";
                echo "<td>" . $result['created_at'] . "</td>";
                echo "<td>" . $result['last_updated'] . "</td>";
                if ($result !== false) {
                    echo "<td><a href='dashboard.php?ua=updateAbout.php&id=$aboutID.php'>Bewerken</a>";
                    echo "<td><a onclick='return confirm(\"Weet u zeker dat u dit bericht wilt verwijderen ? \")' href='dashboard.php?da=deleteAbout.php&id=$aboutID'>Verwijderen</a>";
                }
            ?>
            </tbody>
        </table>
    </div>
</div>