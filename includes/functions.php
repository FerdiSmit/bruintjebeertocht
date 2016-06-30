<?php

function validateInput()
{
    global $usernameErr, $emailErr, $passErr, $confPassErr;

    $username = checkData($_POST['username']);
    $email = checkData($_POST['email']);
    $password = checkData($_POST['password']);
    $confirmPassword = checkData($_POST['confirmPassword']);
    
    if (empty($username))
    {
        $error['username'] = 'Vul alstublieft een gebruikersnaam in';
    }

    if (empty($email))
    {
        $error['email'] = 'Vul alstublieft een emailadres in';
    }
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        $error['email'] = 'Het ingevulde emailadres is niet geldig.';
    }

    if (empty($password))
    {
        $error['password'] = 'Vul alstublieft een wachtwoord in';
    }
    else if (strlen($password) < 6)
    {
        $error['password'] = 'Het wachtwoord moet minstens 6 tekens lang zijn.';
    }
    else if (!preg_match('/^\S*(?=\S*[a-z])(?=\S*[A-Z])(?=\S*[\d])\S*$/', $password))
    {
        $error['password'] = 'Het wachtwoord moet over minstens over 1 kleine letter, 1 hoofdletter en 1 nummer beschikken.';
    }
    
    if (empty($confirmPassword))
    {
        $error['confPass'] = 'Bevestig alstublieft uw wachtwoord';
    }
    else if ($password != $confirmPassword)
    {
        $error['confPass'] = 'De wachtwoorden komen niet met elkaar overeen.';
    }
    
    if (isset($error))
    {
        foreach ($error as $key => $value)
        {
            if ($key == 'username')
            {
                $usernameErr = $value;
            }
            else if ($key == 'email')
            {
                $emailErr = $value;
            }
            else if ($key == 'password')
            {
                $passErr = $value;
            }
            else if ($key == 'confPass')
            {
                $confPassErr = $value;
            }
        }
    }
}

function checkData($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}