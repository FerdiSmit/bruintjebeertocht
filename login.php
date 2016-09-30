<?php
include('includes/functions.php');

if ($user->is_logged_in())
{
    header('Location: dashboard/dashboard.php');
}

if (isset($_POST['submit']))
{
    checkLogin();
}

?>
<head>
    <title><?php if (isset($title)) { echo $title; }?></title>
    <link rel="stylesheet" type="text/css" href="css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="css/jquery-ui.css">

    <link href='https://fonts.googleapis.com/css?family=Architects+Daughter' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" type="text/css" href="css/main.css">

</head>
<div class="container">
    <div class="row">
        <div class="col-xs-12">
            <h2>Login</h2>
            <hr/>
            <?php
            if (isset($loginError))
            {
                echo '<span class="error">' . $loginError . '</span>';
            }
            ?>
        </div>
    </div>
    <form role="form" method="post" class="form-horizontal">
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
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label for="password" class="col-xs-12 col-sm-2 control-label">Wachtwoord</label>
                <div class="col-xs-12 col-sm-6">
                    <input type="password" class="form-control" name="password" id="password" placeholder="Wachtwoord...">
                    <?php
                    if (isset($passwordErr))
                    {
                        echo '<span class="error">' . $passwordErr . '</span>';
                    }
                    ?>
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