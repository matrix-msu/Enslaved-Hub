<?php
// TO RUN: php -r "require 'webcrawler.php'; run();"

require_once(realpath(dirname(__FILE__) . "/../config.php"));
error_reporting(0);

class WebCrawler {

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

    public function instantiateMySQLi() {
        $mysqli = new mysqli($this->host, $this->user, $this->password, $this->dbName);

        if (mysqli_connect_errno())
            printf("Connect failed: %s\n", mysqli_connect_error());

        return $mysqli;
    }

    public function save_broken_link($url, $status_code) {
        $mysqli = $this->instantiateMySQLi();

        if ($stmt = $mysqli->prepare("INSERT INTO broken_links (link_url, error_code) VALUES (?, ?)")) {
            $stmt->bind_param("ss", $url, $status_code);
            $stmt->execute();
            $stmt->close();
        }

        $mysqli->close();
    }

    public function save_keyword($url) {
        $mysqli = $this->instantiateMySQLi();

        $doc = new DOMDocument();
        libxml_use_internal_errors(true);
        $doc->loadHTMLFile($url);
        libxml_clear_errors();

        $elements = $doc->getElementsByTagName('a');

        $params = $keywords = [];
        $types = $values = '';

        // Finding keywords
        foreach($elements as $element) {
            $tag = $element->parentNode->tagName;
            if(
                ($tag == 'h1' || $tag == 'h2' || $tag == 'h3') &&
                !is_numeric($element->nodeValue)
            ) {
                if ($keyword != '') {
                    $keyword = preg_replace('/\s+/', ' ', $element->nodeValue);
                    $keyword = preg_replace('/[^A-Za-z0-9 ]/', '', $keyword);

                    array_push($keywords, $keyword);
                }
            }
        }

        // Building statement
        for ($i=0; $i < count($keywords); $i++) {
            $types .= 'ss';
            $values .= '(?, ?)';
            array_push($params, $keywords[$i]);
            array_push($params, $url);
            if ($i < count($keywords) - 1)
                $values .= ', ';
        }

        $stmt = $mysqli->stmt_init();

        if ($stmt = $mysqli->prepare("INSERT INTO crawler_keywords (keyword, url) VALUES " . $values)) {
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $stmt->close();
        }

        $mysqli->close();
    }

    public function crawl() {
        $mysqli = $this->instantiateMySQLi();
        $results = $mysqli->query("SELECT htmlURL from crawler_seeds");
        $rows = $results->fetch_all(MYSQLI_ASSOC);

        $valid_urls = $broken_urls = [];

        echo 'Crawl Initialized.' . PHP_EOL;
        echo 'Attempting to curl ' . count($rows) . ' urls.' . PHP_EOL;

        foreach($rows as $row) {
            $url = $row['htmlURL'];

            $url = filter_var($url, FILTER_SANITIZE_URL);

            if (filter_var($url, FILTER_VALIDATE_URL)) {
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_HEADER, true);
                curl_setopt($ch, CURLOPT_NOBODY, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 5);
                curl_exec($ch);
                $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($status_code = 200) {
                    echo $url . ' is valid.' . PHP_EOL;
                    array_push($valid_urls, $url);
                } else {
                    echo $url . ' is not valid.' . PHP_EOL;
                    array_push($broken_urls, array($url, $status_code));
                }
            } else {
                echo $url . ' is not valid.' . PHP_EOL;
                array_push($broken_urls, array($url, 0));
            }
        }

        $results->free_result($result);
        $mysqli->close($link);

        echo 'Crawl Complete' . PHP_EOL;
    }
}

function run() {
    $crawler = new WebCrawler();
    $crawler->crawl();
    // $crawler->save_keyword('https://www.kwasikonadu.info/blog');
}

?>
