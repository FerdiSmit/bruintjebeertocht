<?php
include('dashboard.php');
?>

<div class="users">
    <div class="col-xs-10">

        <a href="addUser.php">Gebruiker toevoegen</a>

        <table class="table table-condensed table-responsive">
            <thead>
                <tr>
                    <th>Naam</th>
                    <th>Email</th>
                </tr>
            </thead>
            <tbody>
            <?php
            $users = getUsers();

            foreach ($users as $user)
            {
                echo '<tr>';
                echo '<td>' . $user['username'] . '</td>';
                echo '<td>' . $user['email'] . '</td>';
                echo '<tr/>';
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

<?php
include('footer.php');
?>
