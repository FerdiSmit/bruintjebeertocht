<?php

include('dashboard.php');

$result = getUserByEmail($_SESSION['email']);

if (!$user->is_logged_in())
{
    header('Location: login.php');
}
elseif ($result['email'] != $_SESSION['email'])
{
    $user->logout();
}
else
{
    deleteCharity();
}