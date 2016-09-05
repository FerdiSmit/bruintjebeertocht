<?php

$results = getAlbums();

?>

<a href="dashboard.php?ala=addAlbum.php">Album Toevoegen</a>

<div class="album">
    <div class="col-xs-12">
        <table class="table table-responsive table-condensed">
            <thead>
                <tr>
                    <th>Titel</th>
                    <th>Omschrijving</th>
                    <th>Aangemaakt op</th>
                    <th>Bewerkt op</th>
                    <th>Foto's toevoegen</th>
                    <th>Bewerken</th>
                    <th>Verwijderen</th>
                </tr>
            </thead>
            <tbody>
            <?php
            foreach ($results as $result)
            {
                $albumID = $result['albumID'];

                echo '<tr>';
                echo "<td><a href='dashboard.php?po=pictureOverview.php&id=$albumID'>" . $result['title'] . "</a></td>";
                echo '<td>' . $result['description'] . '</td>';
                echo '<td>' . $result['created_at'] . '</td>';
                if (empty($result['updated_at']))
                {
                    echo '<td>N.v.t.</td>';
                }
                else
                {
                    echo '<td>' . $result['updated_at'] . '</td>';
                }
                echo "<td><a href='dashboard.php?ap=addPictures.php&id=$albumID'>Toevoegen</a></td>";
                echo "<td><a href='dashboard.php?ula=updateAlbum&id='>Bewerken</a></td>";
                echo "<td><a href='dashboard.php?dla=deleteAlbum&id='>Verwijderen</a></td>";
                echo '</tr>';
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
