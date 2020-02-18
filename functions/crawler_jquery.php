<?php
//******************************************************************************   Keywords Queries
// initialize connection
require_once(BASE_PATH . "models/crawler_keywords.php");
require_once(BASE_PATH . "models/crawler_tags.php");
require_once(BASE_PATH . "models/crawler_deleted_keywords.php");
require_once(BASE_PATH . "models/crawler_broken_links.php");
require_once(BASE_PATH . "models/crawler_seeds.php");

$limit = 40;
$offset = 0;
$sort = 'ASC';
$terms = '';
$tagIds = [];
// connect to keywords, broken links and deleted keywords databases
$crawler_keywords = new crawler_keywords();
$crawler_tags = new crawler_tags();
$crawler_deleted_keywords = new crawler_deleted_keywords();
$broken_links = new crawler_broken_links();
$seeds = new crawler_seeds();

//Get limit, offset and sort values
if(isset($_POST["limit"]))
{
	$limit = $_POST["limit"];
}
if(isset($_POST["offset"]))
{
	$offset = $_POST["offset"];
}
if(isset($_POST["sort"]))
{
	$sort = $_POST["sort"];
}
if(isset($_POST["terms"]))
{
	$terms = $_POST["terms"];
}
if(isset($_POST["tag_ids"]))
{
	$tagIds = $_POST["tag_ids"];
}

//Gets results for results tab
if(isset($_POST["get_results"]))
{
	$results = $keyword_ids = [];
	$results['keywords'] = $crawler_keywords->get_keywords($limit,$offset,$sort,$terms,$tagIds);
	foreach ($results['keywords'] as $key => $value) {
		array_push($keyword_ids, $value['keyword_id']);
	}
	$keyword_ids = array_unique($keyword_ids);
	$results['tags'] = $crawler_tags->get_tag_name_per_keyword_ids($keyword_ids);
	echo(json_encode($results));
}

if(isset($_POST["update_tags"]))
{
	$results = $crawler_tags->update_keyword_tags($_POST['id'], $tagIds);
	echo(json_encode('success'));
}

// this functuion will just write a keyword to deleted file
if(isset($_POST["delete_result"]))
{
	$crawler_deleted_keywords->add_to_deleted($_POST["delete_result"]);
	echo(json_encode("success"));
}

if(isset($_POST["get_tags"]))
{
	$results = $crawler_tags->get_tags();
	echo(json_encode($results));
}

//Load more button
if (isset($_POST["more"]))
{
	$results = $crawler_keywords->get_keywords($limit,$_POST["more"]);
	echo(json_encode($results));
}

//filter by date
if (isset($_POST["date"])&& isset($_POST["idx"]))
{
	$results = $crawler_keywords->get_keywords_date($limit,$_POST["idx"],$_POST["date"]);
	echo(json_encode($results));
}

//get results count
if(isset($_POST["count_results"]))
{
	$result = $crawler_keywords->get_count();
	echo(json_encode($result));
}


//Gets results for results tab for visible results
if(isset($_POST["get_results_visible"]))
{
	$results = $keyword_ids = [];
	$results['keywords'] = $crawler_keywords->get_keywords_visible($limit,$offset,$sort,$terms,$tagIds);
	foreach ($results['keywords'] as $key => $value) {
		array_push($keyword_ids, $value['keyword_id']);
	}
	$results['tags'] = $crawler_tags->get_tag_name_per_keyword_ids($keyword_ids);
	echo(json_encode($results));
}

//get results count
if(isset($_POST["count_results_visible"]))
{
	$result = $crawler_keywords->get_count_visible();
	echo(json_encode($result));
}

//filter by date
//if (isset($_POST["date"])&& isset($_POST["MORE"]))
//{
//	$results=$crawler_keywords->get_keywords_date($limit,$_POST["idx"],$_POST["date"]);
//	echo(json_encode($results));
//}

//**********************************************************************************   Broken Links Queries
// this function gets broken links
if(isset($_POST['get_links']))
{
	$results = $broken_links->get_broken_links($limit, $offset);

	echo(json_encode($results));
}

if(isset($_POST["update-link"]))
{
	$crawler_keywords->update_keyword($_POST['id'],$_POST['update-name']);
	$crawler_keywords->update_link($_POST['id'],$_POST['update-link']);

	echo(json_encode("true"));
}

//this function deletes a link from the seeds file and the broken links list
if(isset($_POST["delete_link"]))
{
	// delete from seeds first
	$broken_links->delete_seeds($_POST["delete_link"]);

	// broken links list
	$broken_links->delete_broken_links($_POST["delete_link"]);

	echo(json_encode("true"));
}

//get broken links count
if(isset($_POST["count_links"]))
{
	$result = $broken_links->get_count();
	echo(json_encode($result));
}
//*************************************************************************************   Seeds Queries

if(isset($_POST['get_seeds']))
{
	$results = $seeds->get_seeds($limit, $offset);

	echo(json_encode($results));
}

if(isset($_POST['get_seed_urls']))
{
	$results = $seeds->get_all_urls();

	echo(json_encode($results));
}
if (isset($_POST["more_seeds"]))
{
	$results = $seeds->get_seeds($limit,$_POST["more_seeds"]);

	echo(json_encode($results));
}

if(isset($_POST['update_seed']))
{
	$seeds->update_seed_info($_POST['id'],$_POST['name'],$_POST['title'],$_POST['rss'],$_POST['url'],$_POST['twitter']);

	echo(json_encode("true"));
}

if(isset($_POST['delete_seed']))
{
	$seeds->delete_seed_by_id($_POST['id']);

	echo(json_encode("true"));
}

//get seeds count
if(isset($_POST["count_seeds"]))
{
	$result = $seeds->get_count();

	echo(json_encode($result));
}

//add a seed
if(isset($_POST["add_seed"]))
{
	if ($_POST["add_seed"]) {
		$seeds->add_seed($_POST["name"], $_POST["name"], $_POST["add_seed"]);
	} else {
		$html = file_get_contents($_POST["url"]);
		$title = explode('<title>', $html);

		if(count($title)>1){
			$title = explode('</title>', $title[1]);
			$seeds->add_seed(htmlentities($title[0]), htmlentities($title[0]), $_POST["url"]);
		} else {
			$seeds->add_seed('', '', $_POST["url"]);
		}
	}

	echo(json_encode('success'));
}
?>
