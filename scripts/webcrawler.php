<html><head><title>Matrix WebCrawler</title></head></html>
<?php
require_once(realpath(dirname(__FILE__) . "/../config.php"));
include_once("get_links_functions.php");
error_reporting(0);

class WebCrawler {

    var $host;
    var $user;
    var $dbName;
    var $password;

    public function __construct() {
        $this->host=Host;
        $this->user=Username;
        $this->dbName=DBName;
        $this->password=Password;
    }

    public function connect() {
        $con = mysqli_connect($this->host,$this->user,$this->password,$this->dbName);

        if (mysqli_connect_errno())
            return;

        return $con;
    }

    public function save_broken_link($url, $status_code) {
        $link = $this->connect();

        if ($stmt = mysqli_prepare($link, "INSERT INTO broken_links (link_url, error_code ) VALUES (?, ?)")) {
            mysqli_stmt_bind_param($stmt, "ss", $url, $status_code);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
        }
    }

    public function save_keyword($url) {
        return;
    }

    public function crawl() {
        $link = $this->connect();
        $query = "SELECT htmlURL from crawler_seeds";
        $result = mysqli_query($link, $query);
        $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

        foreach($rows as $row) {
            $url = $row['htmlURL'];

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            $data = curl_exec($ch);
            $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($status_code = 200) {
                save_keyword($url);
            } else {
                save_broken_link($url, $status_code);
            }
        }

        mysqli_free_result($result);
        mysqli_close($link);
    }
}
?>
