<?php

?>

<a href="dashboard.php?cp=createPoll.php">Toevoegen</a>

<div class="row">
    <div class="col-xs-12">
        <table class="table table-responsive table-condensed">
            <thead>
                <tr>
                    <th>Selecteren</th>
                    <th>Vraag</th>
                    <th>Bewerken</th>
                    <th>Verwijderen</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $results = getQuestion();

                foreach ($results as $result)
                {
                    echo '<tr>';
                    echo '<td><input type="radio" name="selectedPoll"></td>';
                    echo '<td>' . $result['question'] . '</td>';
                    echo '<td><a href="dashboard.php?up=updatePoll.php">Bewerken</a>';
                    echo '<td><a onclick=\'return confirm("Weet u zeker dat u deze poll wilt verwijderen?")\' href="dashboard.php?dp=deletePoll.php">Verwijderen</a>';
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
