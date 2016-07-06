<?php
include('includes/functions.php');
require('header.php');
?>

<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <h2>Login</h2>
            <hr/>
        </div>
    </div>
    <form role="form" method="post" action="dashboard.php" class="form-horizontal">
        <div class="row">
            <div class="form-group">
                <label for="username" class="col-xs-12 col-sm-2 control-label">Gebruikersnaam</label>
                <div class="col-xs-12 col-sm-6">
                    <input type="text" class="form-control" name="username" id="username" placeholder="Gebruikersnaam..." value="<?php if (isset($_POST['username'])) { echo htmlspecialchars($_POST['username']); } ?>">
                </div>
            </div>
            <div class="form-group">
                <label for="password" class="col-xs-12 col-sm-2 control-label">Wachtwoord</label>
                <div class="col-xs-12 col-sm-6">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Wachtwoord...">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4 col-sm-offset-2">
                    <input type="submit" name="submit" class="btn btn-primary" value="Login">
                </div>
            </div>
        </div>
    </form>
</div>