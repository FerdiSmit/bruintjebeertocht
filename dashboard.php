<?php
require('includes/config.php');
include('header.php');

if (!$user->is_logged_in())
{
    header('Location: login.php');
}
?>

<a href="logout.php">Loguit</a>

<div class="container">
    <div class="menu-sidebar">
        <ul
    </div>
</div>