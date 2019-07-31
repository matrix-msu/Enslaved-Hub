<?php
//******************************************************************************   Keywords Queries
// initialize connection
require_once(BASE_PATH . "assets/webcrawler/models/crawler_keywords.php");
require_once(BASE_PATH . "assets/webcrawler/models/crawler_deleted_keywords.php");
require_once(BASE_PATH . "assets/webcrawler/models/crawler_broken_links.php");
require_once(BASE_PATH . "assets/webcrawler/models/crawler_seeds.php");

$limit = 20;
$offset = 0;
// connect to keywords, broken links and deleted keywords databases
$crawler_keywords =new crawler_keywords();
$crawler_deleted_keywords =new crawler_deleted_keywords();
$broken_links=new crawler_broken_links();
$seeds= new crawler_seeds();


//Gets results for results tab
if(isset($_POST["get_results"]))
{
	$results = $crawler_keywords->get_keywords($limit,$offset);
	echo(json_encode($results));
}

// this functuion will just write a keyword to deleted file
if(isset($_POST["deleted"]))
{
	$crawler_deleted_keywords->add_to_deleted($_POST["deleted"]);
	echo(json_encode("true"));
}

//Load more button
if (isset($_POST["MORE"]))
{
	$results = $crawler_keywords->get_keywords($limit,$_POST["MORE"]);
	echo(json_encode($results));
}

//filter by date
if (isset($_POST["date"])&& isset($_POST["idx"]))
{
	$results = $crawler_keywords->get_keywords_date($limit,$_POST["idx"],$_POST["date"]);
	echo(json_encode($results));
}

//filter by date
//if (isset($_POST["date"])&& isset($_POST["MORE"]))
//{
//	$results=$crawler_keywords->get_keywords_date($limit,$_POST["idx"],$_POST["date"]);
//	echo(json_encode($results));
//}

//**********************************************************************************   Broken Links Queries
// this function edits a given link in the seeds file
if(isset($_POST["update_link"]))
{
	// update seeds first
	$broken_links->update_seeds($_POST["old_link"],$_POST["update_link"]);
	// broken links list
	$broken_links->delete_broken_links($_POST["old_link"]);

	echo(json_encode("true"));
}

//this function deletes a link from the seeds file and the broken links list
if(isset($_POST["del_row"]))
{
	// delete from seeds first
	$broken_links->delete_seeds($_POST["del_row"]);

	// broken links list
	$broken_links->delete_broken_links($_POST["del_row"]);

	echo(json_encode("true"));
}

// this function gets broken links
if(isset($_POST['get_links']))
{
	$results = $broken_links->get_broken_links($offset);
	echo(json_encode($results));
}
//*************************************************************************************   Seeds Queries

if(isset($_POST['get_seeds']))
{
	$results = $seeds->get_seeds($limit, $offset);
	echo(json_encode($results));
}
if (isset($_POST["moreSeeds"]))
{
	$results = $seeds->get_seeds($limit,$_POST["moreSeeds"]);
	echo(json_encode($results));
}

if(isset($_POST['update_seed_id']))
{
	$seeds->update_seed_info($_POST['update_seed_id'],$_POST['name'],$_POST['title'],$_POST['rss'],$_POST['url'],$_POST['twitter']);
}

if(isset($_POST['delete_seed_id']))
{
	$seeds->delete_seed_info($_POST['delete_seed_id']);
}
?>
