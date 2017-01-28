<?php
include('header.php');

$title = 'Album';

global $paginate;
$record_per_page = 30;
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
                    <ul class="pagination" style="margin-left: 10px;">
                        <?php $paginate->pagingLink(getPicturesForPagination(), $record_per_page); ?>
                    </ul>
                    <table class="table table-condensed table-responsive">
                    <?php

                    $id = $_GET['id'];
                    $album = getAlbumByNameAndId($id);

                    $rows = getPicturesForPagination();

                    $query = $paginate->paging($rows, $record_per_page);
                    $pictures = $paginate->dataView($query);

                    if (isset($paginateErr))
                    {
                        $paginateErr =  "Er zijn geen foto's in dit album";
                        echo '<td>' . $paginateErr . '</td>';
                    }
                    else
                    {
                        $counter = 1;
                        $imagenum = 0;
                        $albumDir = 'albums/' . $album['title'] .'/';

                        $browser = $_SERVER['HTTP_USER_AGENT'];

                        if ($browser === 'Mozilla/5.0 (Windows NT 10.0; WOW64; Trident/7.0; MAARJS; rv:11.0) like Gecko')
                        {
                            $totalPictures = count($pictures);

                            $imagesPerRow = 4;

                            for ($i = 0; $i < $totalPictures; $i++)
                            {
                                if ($i % $imagesPerRow == 0)
                                {
                                    echo '<br/>';
                                }

                                $picture = $pictures[$i]['picture'];

                                echo '<a href="' . $albumDir . $picture . '" title="" data-dialog><img src="' . $albumDir . $picture . '" class="img-responsive img-thumbnail img-size"></a>';


                            }
                        }
                        else {
                            foreach ($pictures as $picture) {
                                if (!(($counter++) % 4)) {
                                    echo '<td><div class="links"><a href="' . $albumDir . $picture['picture'] . '" title="" data-dialog><img class="img-responsive img-thumbnail" src="' . $albumDir . $picture['picture'] . '"></a></div></td></tr><tr>';
                                } else {
                                    echo '<td><div class="links"><a href="' . $albumDir . $picture['picture'] . '" title="" data-dialog><img class="img-responsive img-thumbnail" src="' . $albumDir . $picture['picture'] . '"></a></div></td>';
                                }
                            }
                        }
                    }


                    ?>
                    </table>

                </div>
            </div>
        </div>
<?php
include('footer.php');
?>