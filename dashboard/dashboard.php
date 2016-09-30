<?php
require('../includes/functions.php');
include('header.php');

if (!$user->is_logged_in())
{
    header('Location: ../login.php');
}
?>

<script>
    tinymce.init({
        selector: '#tinyEditor'
    });
</script>

<div id="admin-header">
    <a href="../logout.php">Loguit</a>
</div>

<div id="admin-panel">
    <div class="row">
        <div class="col-xs-2">
            <div class="sidebar">
                <ul class="list-group">
                    <li class="list-group-item"><a href="news.php">Nieuwsberichten</a></li>
                    <li class="list-group-item"><a href="aboutOverview.php">Over BBT</a></li>
                    <li class="list-group-item"><a href="route.php">Routes</a></li>
                    <li class="list-group-item"><a href="ambassador.php">Ambassadeurs</a></li>
                    <li class="list-group-item"><a href="charity.php">Goede Doelen</a></li>
                    <li class="list-group-item"><a href="poll.php">Poll</a></li>
                    <li class="list-group-item"><a href="albumOverview.php">Fotoalbum</a></li>
                </ul>
            </div>
        </div>



