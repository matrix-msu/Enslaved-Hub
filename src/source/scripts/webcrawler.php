<?php
// TO RUN: php -r "require 'path/to/webcrawler.php'; run();"

require_once(realpath(dirname(__FILE__) . "/../database-config.php"));
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

    private function instantiateMySQLi() {
        $mysqli = new mysqli($this->host, $this->user, $this->password, $this->dbName);

        if (mysqli_connect_errno())
            printf("Connect failed: %s\n", mysqli_connect_error());

        return $mysqli;
    }

    private function buildDynamicBindParams($elements) {
        // Params arg must be an array with each element being array(1st, 2nd)
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

    public function save_broken_link($links) {
        $mysqli = $this->instantiateMySQLi();

        echo 'Saving ' . count($links) . ' broken links into the database...' . PHP_EOL;

        list($types, $values, $params) = $this->buildDynamicBindParams($links);

        if ($stmt = $mysqli->prepare("INSERT INTO broken_links (link_url, error_code) VALUES " . $values)) {
            $stmt->bind_param($types, ...$params);
            $stmt->execute();
            $stmt->close();
        }

        $mysqli->close();
    }

    public function save_keywords($urls) {

        $params = $keywords = [];
        $types = $values = '';

        foreach ($urls as $url) {
            echo 'Attempting to scrape ' . $url . PHP_EOL;
            $count = 0;

            $doc = new DOMDocument();
            libxml_use_internal_errors(true);
            $doc->loadHTMLFile($url);
            libxml_clear_errors();

            $elements = $doc->getElementsByTagName('a');

            // Finding keywords
            foreach($elements as $element) {
                $tag = $element->parentNode->tagName;
                //use this, validate first
                foreach (['h1', 'h2', 'h3'] as $header) {
                    if($tag == $header && !is_numeric($element->nodeValue)) {
                        $keyword_url =  $element->getAttribute('href');
                        $keyword = preg_replace('/\s+/', ' ', $element->nodeValue);
                        $keyword = preg_replace('/[^A-Za-z0-9 ]/', '', $keyword);

                        if (
                            $keyword != '' &&
                            filter_var(
                                $keyword_url,
                                FILTER_VALIDATE_URL,
                                FILTER_FLAG_SCHEME_REQUIRED
                            )
                        ) {
                            array_push($keywords, array($keyword => $keyword_url));

                            $count++;
                        }
                    }
                }
            }
            echo 'Found ' . $count . ' keywords for ' . $url . PHP_EOL . PHP_EOL;
        }

        if (count($keywords) > 0) {
            echo 'Saving ' . count($keywords) . ' keywords into the database...' . PHP_EOL;

            list($types, $values, $params) = $this->buildDynamicBindParams($keywords);

            //create/insert into crawler_seeds
            $mysqli = $this->instantiateMySQLi();

            $stmt = $mysqli->stmt_init();

            if ($stmt = $mysqli->prepare("INSERT IGNORE INTO crawler_keywords (keyword, url) VALUES " . $values)) {
                $stmt->bind_param($types, ...$params);
                $stmt->execute();
                $stmt->close();
                echo 'Save successful!' . PHP_EOL;
            }

            // Updating new keywords with No Display tag
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
            $mysqli->close();
        }
    }

    public function crawl() {
        $mysqli = $this->instantiateMySQLi();
        $results = $mysqli->query("SELECT htmlURL from crawler_seeds");
        $rows = $results->fetch_all(MYSQLI_ASSOC);

        $valid_urls = $broken_urls = [];

        echo 'Crawl Initialized.' . PHP_EOL;
        echo 'Attempting to curl ' . count($rows) . ' urls...' . PHP_EOL;

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
                $status_code = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
                curl_close($ch);

                if ($status_code >= 200 && $status_code <= 400) {
                    echo $url . ' returned a successful http response code.' . PHP_EOL;
                    array_push($valid_urls, $url);
                } else {
                    echo $url . ' returned an http response error code or cannot reach host.' . PHP_EOL;
                    array_push($broken_urls, array($url, $status_code));
                }
            } else {
                echo $url . ' is not a valid url.' . PHP_EOL;
                array_push($broken_urls, array($url, 1));
            }

            echo PHP_EOL;
        }

        $results->free_result($result);
        $mysqli->close($link);

        if (count($valid_urls) > 0) {
            echo 'Attempting to retrieve keywords...' . PHP_EOL;
            $this->save_keywords($valid_urls);
        }

        if (count($broken_urls) > 0) {
            echo 'Attempting to save broken urls...' . PHP_EOL;
            $this->save_broken_link($broken_urls);
        }

        echo 'Crawl Complete!' . PHP_EOL;
    }
}

function run() {
    $crawler = new WebCrawler();
    $crawler->crawl();
}

?>
