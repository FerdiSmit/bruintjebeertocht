<?php

class Paginate
{
    private $_db;

    function __construct($db)
    {
        $this->_db = $db;
    }

    public function dataView($query)
    {
        global $paginateErr;

        $stmt = $this->_db->prepare($query);
        $stmt->execute();

        if ($stmt->rowCount() > 0)
        {
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $results;
        }
        else
        {
            $paginateErr = 'Er staat niets in de database';
            return $paginateErr;
        }
    }

    public function paging($query, $records_per_page)
    {
        $starting_position = 0;
        if (isset($_GET['page_no']))
        {
            $starting_position = ($_GET['page_no'] - 1) * $records_per_page;
        }

        $newQuery = $query  . " LIMIT $starting_position, $records_per_page";

        return $newQuery;
    }

    public function pagingLink($query, $records_per_page)
    {
        $self = $_SERVER['PHP_SELF'];

        $stmt = $this->_db->prepare($query);
        $stmt->execute();

        $total_no_of_records = $stmt->rowCount();

        if ($total_no_of_records > 0)
        {
            ?>
            <tr>
                <td colspan="3">
                    <?php
                    $total_no_of_pages = ceil($total_no_of_records / $records_per_page);

                    $current_page = 1;

                    if (isset($_GET['page_no']))
                    {
                        $current_page = $_GET['page_no'];
                    }

                    if ($current_page != 1)
                    {
                        if (isset($_GET['id']))
                        {
                            $id = $_GET['id'];

                            $previous = $current_page - 1;
                            echo "<li><a href='" . $self . "?id=" . $id . "&page_no=" . $previous . "'>Vorige</a></li>";
                        }
                        else
                        {
                            $previous = $current_page - 1;
                            echo "<li><a href='" . $self . "?page_no=" . $previous . "'>Vorige</a></li>";
                        }

                    }

                    for ($i = 1; $i <= $total_no_of_pages; $i++)
                    {
                        if ($i == $current_page)
                        {
                            if (isset($_GET['id']))
                            {
                                $id = $_GET['id'];

                                echo "<li><a href='" . $self . "?id=" . $id . "&page_no=" . $i . "'>$i</a></li>";
                            }
                            else
                            {
                                echo "<li><a href='" . $self . "?page_no=" . $i . "'>" . $i . "</a></li>";
                            }
                        }
                        else
                        {
                            if (isset($_GET['id']))
                            {
                                $id = $_GET['id'];

                                echo "<li><a href='" . $self . "?id=" . $id . "&page_no=" . $i . "'>$i</a></li>";
                            }
                            else
                            {
                                echo "<li><a href='" . $self . "?page_no=" . $i . "'>" . $i . "</a></li>";
                            }
                        }
                    }

                    if ($current_page != $total_no_of_pages)
                    {
                        $next = $current_page + 1;

                        if (isset($_GET['id']))
                        {
                            $id = $_GET['id'];

                            echo "<li><a href='" . $self . "?id=" . $id . "&page_no=" . $next . "'>Volgende</a></li>";
                        }
                        else
                        {
                            echo "<li><a href='" . $self . "?page_no=" . $next . "'>Volgende</a></li>";
                        }
                    }
                    ?>
                </td>
            </tr>
            <?php
        }
    }
}