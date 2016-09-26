<?php
include('dashboard.php');
?>

<a href="createPoll.php">Toevoegen</a>

<div id="poll">
    <div class="col-xs-10">
        <table class="table table-responsive table-condensed">
            <thead>
                <tr>
                    <th>Selecteren</th>
                    <th>Vraag</th>
                    <th>Datum aangemaakt</th>
                    <th>Datum bewerkt</th>
                    <th>Bewerken</th>
                    <th>Verwijderen</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $results = getQuestion();

                foreach ($results as $result)
                {
                    $pollID = $result['pollID'];

                    echo "<tr>";
                    echo "<td><input type='radio' name='selectedPoll'></td>";
                    echo "<td>" . $result['question'] . "</td>";
                    echo "<td>" . $result['created_at'] . "</td>";
                    if (empty($result['updated_at']))
                    {
                        echo "<td>n.v.t.</td>";
                    }
                    else
                    {
                        echo "<td>" . $result['updated_at'] . "</td>";
                    }
                    echo "<td><a href='updatePoll.php?id=$pollID'>Bewerken</a>";
                    echo "<td><a onclick='return confirm(\"Weet u zeker dat u dit bericht wilt verwijderen?\")' href='deletePoll.php?id=$pollID'>Verwijderen</a>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

<?php
include('footer.php');
?>