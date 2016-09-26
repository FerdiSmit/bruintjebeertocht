
<?php
include('dashboard.php');

$result = getAbout();

if ($result === false)
{
    echo '<a href="addAbout.php">Toevoegen</a>';
}
?>

<div id="about">
    <div class="col-xs-10">
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
                    echo "<td><a href='updateAbout.php?id=$aboutID.php'>Bewerken</a>";
                    echo "<td><a onclick='return confirm(\"Weet u zeker dat u dit bericht wilt verwijderen ? \")' href='deleteAbout.php?id=$aboutID'>Verwijderen</a>";
                }
            ?>
            </tbody>
        </table>
    </div>
</div>

<?php
include('footer.php');
?>