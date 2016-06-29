<?php
require('header.php');
?>

<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <h2>Registreren</h2>
            <hr/>
        </div>
    </div>
    <form role="form" method="post" action="" class="form-horizontal">
        <div class="row">
            <div class="form-group">
                <label for="username" class="col-sm-2 control-label">Gebruikersnaam</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="username" placeholder="Gebruikersnaam...">
                </div>
            </div>
            <div class="form-group">
                <label for="email" class="col-sm-2 control-label">Email</label>
                <div class="col-sm-6">
                    <input type="text" class="form-control" id="email" placeholder="Email...">
                </div>
            </div>
            <div class="form-group">
                <label for="password" class="col-sm-2 control-label">Wachtwoord</label>
                <div class="col-sm-6">
                    <input type="password" class="form-control" id="password" placeholder="Wachtwoord...">
                </div>
            </div>
            <div class="form-group">
                <label for="confirmPassword" class="col-sm-2 control-label">Bevestig Wachtwoord</label>
                <div class="col-sm-6">
                    <input type="password" class="form-control" id="confirmPassword" placeholder="Bevestig wachtwoord...">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4 col-sm-offset-2">
                    <input type="submit" class="btn btn-primary" value="Registreer">
                </div>
            </div>
        </div>
    </form>
</div>