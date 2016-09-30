<?php

include('dashboard.php');

$result = getUserByName($_SESSION['username']);

if (!$user->is_logged_in())
{
    header('Location: login.php');
}
elseif ($result['username'] != $_SESSION['username'])
{
    $user->logout();
}
else
{
    deleteCharity();
}