<?php
require('includes/functions.php');
include('header.php');

if (!$user->is_logged_in())
{
    header('Location: login.php');
}
?>

<script>
    tinymce.init({
        selector: '#newssection'
    });
</script>

<div id="admin-header">
    
</div>

<div id="admin-panel">
    <div class="container">
        <div class="row">
            <div class="col-xs-2">
                <div class="sidebar">
                    <ul class="list-group">
                        <li class="list-group-item"><a href="?n=news.php">Nieuwsberichten</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-xs-10">
                <div class="content">
                    <?php
                    if (isset($_GET['n']))
                    {
                        include('news.php');
                    }
                    else if (isset($_GET['c']))
                    {
                        include('createNews.php');
                    }
                    else if (isset($_GET['u']))
                    {
                        include('update.php');
                    }
                    else if (isset($_GET['d']))
                    {
                        include('delete.php');
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>