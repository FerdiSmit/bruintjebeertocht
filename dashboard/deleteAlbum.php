<?php

include('dashboard.php');

$results = getUserByName($_SESSION['username']);

if (!$user->is_logged_in())
{
    header('Location: login.php');
}
else if ($results['username'] != $_SESSION['username'])
{
    $user->logout();
}
else
{
    deleteAlbum();
}