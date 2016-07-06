<?php
include('includes/functions.php');
require('header.php');

if (isset($_POST['submit']))
{
    validateInput();
}
?>

<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <h2>Registreer Gebruiker</h2>
            <hr/>
        </div>
    </div>
    <form role="form" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="form-horizontal">
        <div class="row">
            <div class="form-group">
                <label for="username" class="col-xs-12 col-sm-2 control-label">Gebruikersnaam</label>
                <div class="col-xs-12 col-sm-6">
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
            </div>
            <div class="form-group">
                <label for="email" class="col-sm-2 control-label">Email</label>
                <div class="col-sm-6">
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
            </div>
            <div class="form-group">
                <label for="password" class="col-sm-2 control-label">Wachtwoord</label>
                <div class="col-sm-6">
                    <input type="password" name="password" class="form-control" id="password" placeholder="Wachtwoord...">
                    <?php
                    if (isset($passErr))
                    {
                        echo '<span class="error">' . $passErr . '</span>';
                    }
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="confirmPassword" class="col-sm-2 control-label">Bevestig Wachtwoord</label>
                <div class="col-sm-6">
                    <input type="password" name="confirmPassword" class="form-control" id="confirmPassword" placeholder="Bevestig wachtwoord...">
                    <?php
                    if (isset($confPassErr))
                    {
                        echo '<span class="error">' . $confPassErr . '</span>';
                    }
                    ?>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4 col-sm-offset-2">
                    <input type="submit" name="submit" class="btn btn-primary" value="Registreer">
                </div>
            </div>
        </div>
    </form>
</div>