<?php
// Script for adding No Display tag to Existing crawler_keywords with no tag
// TO RUN: php -r "require 'path/to/add_no_display.php'; run();"

require_once(realpath(dirname(__FILE__) . "/../database-config.php"));

class AddNoDisplay {

    var $host;
    var $user;
    var $dbName;
    var $password;

    public function __construct() {
        $this->host = Host;
        $this->user = Username;
        $this->dbName = DBName;
        $this->password = Password;
    }

    private function instantiateMySQLi() {
        $mysqli = new mysqli($this->host, $this->user, $this->password, $this->dbName);

        if (mysqli_connect_errno())
            printf("Connect failed: %s\n", mysqli_connect_error());

        return $mysqli;
    }

    private function buildDynamicBindParams($elements) {
        // Params arg must be an array with each element being array(1st => 2nd)
        $types = $values = '';
        $params = [];

        for ($i=0; $i < count($elements); $i++) {
            foreach ($elements[$i] as $first => $second) {
                array_push($params, $first);
                array_push($params, $second);
                break;
            }

            $types .= 'ss';
            $values .= '(?, ?)';
            if ($i < count($elements) - 1)
                $values .= ', ';
        }

        return array($types, $values, $params);
    }

    public function migrate() {
        $mysqli = $this->instantiateMySQLi();
        $query = "SELECT keyword_id FROM crawler_keywords WHERE keyword_id NOT IN (
            SELECT keyword_id FROM crawler_keyword_tags_assoc
        )";
        $stmt = mysqli_prepare($mysqli, $query);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        $keywords = mysqli_fetch_all($result, MYSQLI_ASSOC);
        $result->free_result();

        if (count($keywords) > 0) {
            $query = "SELECT tag_id FROM crawler_tags WHERE tag_name = 'No Display'";
            $stmt = mysqli_prepare($mysqli, $query);
            $stmt->execute();
            $result = $stmt->get_result();
            $stmt->close();

            $no_display_tag_id = mysqli_fetch_all($result, MYSQLI_ASSOC)[0]['tag_id'];

            $values = [];
            foreach ($keywords as $value) {
                array_push($values, [$value['keyword_id'] => $no_display_tag_id]);
            }

            $result->free_result();

            list($types, $values, $params) = $this->buildDynamicBindParams($values);

            $stmt = $mysqli->stmt_init();

            if ($stmt = $mysqli->prepare("INSERT INTO crawler_keyword_tags_assoc (keyword_id, tag_id) VALUES " . $values)) {
                $stmt->bind_param($types, ...$params);
                $stmt->execute();
                $stmt->close();
                echo 'Save successful!' . PHP_EOL;
            }
        }

        mysqli_close($mysqli);
    }
}

function run() {
    $migrate = new AddNoDisplay();
    $migrate->migrate();
}

?>