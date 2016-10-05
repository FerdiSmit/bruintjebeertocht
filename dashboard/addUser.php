<?php
include('dashboard.php');

if (isset($_POST['submit']))
{
    validateRegistration();
}
?>

<div class="register">
    <div class="col-xs-10">
        <form role="form" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="form-horizontal">
            <div class="row">
                <div class="form-group col-xs-7">
                    <label for="username">Gebruikersnaam</label>
                    <input type="text" class="form-control" name="username" id="username" placeholder="Gebruikersnaam..." value="<?php if (isset($_POST['username'])) { echo htmlspecialchars($_POST['username']); } ?>">
                    <?php
                    if (isset($usernameErr))
                    {
                        echo '<span class="error">' . $usernameErr . '</span>';
                    }
                    if (isset($usernameExists))
                    {
                        echo '<span class="error">' . $usernameExists . '</span>';
                    }
                    ?>
                </div>
                <div class="form-group col-xs-7">
                    <label for="email">Email</label>
                    <input type="text" name="email" class="form-control" id="email" placeholder="Email..." value="<?php if (isset($_POST['email'])) { echo htmlspecialchars($_POST['email']); } ?>">
                    <?php
                    if (isset($emailErr))
                    {
                        echo '<span class="error">' . $emailErr . '</span>';
                    }
                    if (isset($emailExists))
                    {
                        echo '<span class="error">' . $emailExists . '</span>';
                    }
                    ?>
                </div>
                <div class="form-group col-xs-7">
                    <label for="password">Wachtwoord</label>
                    <input type="password" name="password" class="form-control" id="password" placeholder="Wachtwoord...">
                    <?php
                    if (isset($passErr))
                    {
                        echo '<span class="error">' . $passErr . '</span>';
                    }
                    ?>
                </div>
                <div class="form-group col-xs-7">
                    <label for="confirmPassword">Bevestig Wachtwoord</label>
                    <input type="password" name="confirmPassword" class="form-control" id="confirmPassword" placeholder="Bevestig wachtwoord...">
                    <?php
                    if (isset($confPassErr))
                    {
                        echo '<span class="error">' . $confPassErr . '</span>';
                    }
                    ?>
                </div>
                <div class="form-group col-xs-7">
                    <input type="submit" name="submit" class="btn btn-primary" value="Registreer">
                </div>
            </div>
        </form>
    </div>
</div>

<?php
include('footer.php');
?>
