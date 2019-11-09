<?php

class paginate
{
    public $tableName;
    private $db;

    public function __construct()
    {
        $this->db = new \PDO('mysql:host='.goat::$app->config['db']['host'].';dbname='.goat::$app->config['db']['dbname'], goat::$app->config['db']['username'], goat::$app->config['db']['password']);
    }

    public function dataview($query, $params = []) //$removeFirst = false, $valueIndex = -1, $valueTag = "value", $preTags = ["<td>", "</td>"]
    {
        (isset($params['removeFirst'])) ?: $params['removeFirst'] = false;
        (isset($params['valueIndex'])) ?: $params['valueIndex'] = -1;
        (isset($params['valueTag'])) ?: $params['valueTag'] = 'value';
        (isset($params['preTags'])) ?: $params['preTags'] = ['<td>', '</td>'];

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                if ($params['valueIndex'] >= 0) {
                    echo '<tr '.$params['valueTag'].'="'.htmlentities($row[$params['valueIndex']]).'">';
                } else {
                    echo '<tr>';
                }

                if ($params['removeFirst']) {
                    unset($row['id']);
                }
                //hardcoded for bot status
                if (isset($params['stumble']) && ! empty($params['stumble']) && isset($params['stumbleData']) && is_array($params['stumbleData']) && count($params['stumbleData']) >= 3) {
                    $row[$params['stumble']] = ((1 == $row[$params['stumble']]) ? $params['stumbleData'][2].'Online'.$params['stumbleData'][3] : $params['stumbleData'][0].'Offline'.$params['stumbleData'][1]);
                }
                if (isset($params['flags']) && 'true' == $params['flags']) {
                    $row['country_code'] = '<img src="'.WEB_DIR.'images/flags/'.$row['country_code'].'.gif">';
                }
                foreach ($row as $item) {
                    echo (strlen($item) > 500) ? $params['preTags'][0].substr($item, 0, 500).$params['preTags'][1] : $params['preTags'][0].$item.$params['preTags'][1];
                }
                echo '</tr>';
            }
        } else {
            ?>
            <tr>
                <td>Nothing here...</td>
            </tr>
            <?php
        }
    }

    public function paging($query, $records_per_page)
    {
        $starting_position = 0;
        if (isset($_GET[$this->tableName])) {
            $starting_position = ($_GET[$this->tableName] - 1) * $records_per_page;
        }
        $query2 = $query." limit $starting_position,$records_per_page";

        return $query2;
    }

    public function paginglink($query, $records_per_page)
    {
        $self = strtok($_SERVER['REQUEST_URI'], '?');

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        $total_no_of_records = $stmt->rowCount();

        if ($total_no_of_records > 0) {
            ?>
            <nav>
            <ul class="pagination">
                <?php
                $total_no_of_pages = ceil($total_no_of_records / $records_per_page);
            $current_page = 1;
            if (isset($_GET[$this->tableName])) {
                $current_page = $_GET[$this->tableName];
            }
            if (1 != $current_page) {
                $previous = $current_page - 1;
                echo ' <li>
                              <a href="'.$self.'?'.$this->tableName.'='.$previous.'" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                              </a>
                            </li>';
            }
            for ($i = 1; $i <= $total_no_of_pages; ++$i) {
                if ($i == $current_page) {
                    echo "<li class='active'><a href='".$self.'?'.$this->tableName.'='.$i."' style='text-decoration:none'>".$i.'</a></li>';
                } else {
                    echo "<li class=''><a href='".$self.'?'.$this->tableName.'='.$i."'>".$i.'</a></li>';
                }
            }
            if ($current_page != $total_no_of_pages) {
                $next = $current_page + 1;
                echo '<li>
                          <a href="'.$self.'?'.$this->tableName.'='.$next.'" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                          </a>
                        </li>';
            } ?></ul></nav><?php
        }
    }
}
