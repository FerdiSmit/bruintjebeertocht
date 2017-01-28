<?php

include('dashboard.php');

$results = getUserByEmail($_SESSION['email']);

if (!$user->is_logged_in())
{
    header('Location: login.php');
}
else if ($results['email'] != $_SESSION['email'])
{
    $user->logout();
}
else
{
    deleteAlbum();
}