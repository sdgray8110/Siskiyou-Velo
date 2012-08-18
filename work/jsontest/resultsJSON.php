<?php
class resultsJSON {
    var $query = new dbQuery();
    var $results = $query->fullTableQuery($db,$table,$sort,$order,$select,$where);

    public function displayResults() {
        $result_array[] = "";

        while($row = mysql_fetch_object($this->results)){
            $result_array[]=$row;
        }

        echo json_encode($result_array);
    }
}

class dbQuery {
    private function dbConnect($db) {
        $connection = mysql_connect("localhost","gray8110","8aU_V{^{,RJC");

        if (!$connection) {
            die ("Database connection failed: " . mysql_error());
        }

        $db_select = mysql_select_db("gray8110_".$db,$connection);

        if (!$db_select) {
            die("Database selection failed: " . mysql_error());
        }

        return $connection;
    }

    public function fullTableQuery($db,$table,$sort,$order,$select,$where) {
        $connection = $this->dbConnect($db);
        $select ? $select = $select : $select = '*';
        $where ? $where = ' ' . $where . ' ' : $where = '';
        $query = 'SELECT '.$select.' FROM ' . $table . $where .' ORDER BY ' . $sort . ' ' .$order;

        return mysql_query($query,$connection);
    }
}
?>