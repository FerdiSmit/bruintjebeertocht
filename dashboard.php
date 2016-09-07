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
        selector: '#tinyEditor'
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
                        <li class="list-group-item"><a href="?ao=aboutOverview.php">Over BBT</a></li>
                        <li class="list-group-item"><a href="?p=poll.php">Poll</a></li>
                        <li class="list-group-item"><a href="?alo=albumOverview.php">Fotoalbum</a></li>
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
                        include('updateNews.php');
                    }
                    else if (isset($_GET['d']))
                    {
                        include('deleteNews.php');
                    }
                    else if (isset($_GET['p']))
                    {
                        include('poll.php');
                    }
                    else if (isset($_GET['cp']))
                    {
                        include('createPoll.php');
                    }
                    else if (isset($_GET['ao']))
                    {
                        include('aboutOverview.php');
                    }
                    else if (isset($_GET['aa']))
                    {
                        include('addAbout.php');
                    }
                    else if (isset($_GET['ua']))
                    {
                        include('updateAbout.php');
                    }
                    else if (isset($_GET['da']))
                    {
                        include('deleteAbout.php');
                    }
                    else if (isset($_GET['alo']))
                    {
                        include('albumOverview.php');
                    }
                    else if (isset($_GET['ala']))
                    {
                        include('addAlbum.php');
                    }
                    else if (isset($_GET['ula']))
                    {
                        include('updateAlbum.php');
                    }
                    else if (isset($_GET['dla']))
                    {
                        include('deleteAlbum.php');
                    }
                    else if (isset($_GET['ap']))
                    {
                        include('addPictures.php');
                    }
                    else if (isset($_GET['po']))
                    {
                        include('pictureOverview.php');
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include('footer.php');
?>