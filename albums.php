<?php
include('header.php');

global $paginate;
?>
        <div id="middle" class="col-xs-8">
            <div id="blueimp-gallery-dialog" data-show="blind" data-hide="blind">
                <!-- The gallery widget  -->
                <div class="blueimp-gallery blueimp-gallery-carousel blueimp-gallery-controls">
                    <div class="slides"></div>
                    <a class="prev">‹</a>
                    <a class="next">›</a>
                    <a class="play-pause"></a>
                </div>
            </div>
            <div class="pictures">
                <div class="col-xs-12">
                    <table class="table table-condensed table-responsive">
                    <?php
                    $id = $_GET['id'];
                    $album = getAlbumByNameAndId($id);

                    $rows = getPicturesForPagination();
                    $record_per_page = 30;
                    $query = $paginate->paging($rows, $record_per_page);
                    $pictures = $paginate->dataView($query);

                    $counter = 1;
                    $albumDir = 'albums/' . $album['title'] .'/';
                    ?>
                    <?php
                    foreach ($pictures as $picture)
                    {
                        if (!(($counter++) % 4))
                        {
                            echo '<td><div class="links"><a href="' . $albumDir . $picture['picture'] . '" title="" data-dialog><img class="img-responsive img-thumbnail" src="' . $albumDir . $picture['picture'] . '"></a></div></td></tr><tr>';
                        }
                        else
                        {
                            echo '<td><div class="links"><a href="' . $albumDir . $picture['picture'] . '" title="" data-dialog><img class="img-responsive img-thumbnail" src="' . $albumDir .  $picture['picture'] . '"></a></div></td>';
                        }
                    }
                    ?>
                    </table>
                    <ul class="pagination">
                        <?php $paginate->pagingLink(getPicturesForPagination(), $record_per_page); ?>
                    </ul>
                </div>
            </div>
        </div>
<?php
include('footer.php');
?>