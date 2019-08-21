<?php
//******************************************************************************   Keywords Queries
// initialize connection
require_once(BASE_PATH . "assets/webcrawler/models/crawler_keywords.php");
require_once(BASE_PATH . "assets/webcrawler/models/crawler_deleted_keywords.php");
require_once(BASE_PATH . "assets/webcrawler/models/crawler_broken_links.php");
require_once(BASE_PATH . "assets/webcrawler/models/crawler_seeds.php");

$limit = 40;
$offset = 0;
$sort = 'ASC';
$terms = '';
// connect to keywords, broken links and deleted keywords databases
$crawler_keywords =new crawler_keywords();
$crawler_deleted_keywords =new crawler_deleted_keywords();
$broken_links=new crawler_broken_links();
$seeds= new crawler_seeds();


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


//Gets results for results tab
if(isset($_POST["get_results"]))
{
	$results = $crawler_keywords->get_keywords($limit,$offset,$sort,$terms);
	echo(json_encode($results));
}

// this functuion will just write a keyword to deleted file
if(isset($_POST["delete_result"]))
{
	$crawler_deleted_keywords->add_to_deleted($_POST["delete_result"]);
	echo(json_encode("true"));
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
if (isset($_POST["more_seeds"]))
{
	$results = $seeds->get_seeds($limit,$_POST["more_seeds"]);

	echo(json_encode($results));
}

if(isset($_POST['update_seed']))
{
	$seeds->update_seed_info($_POST['update_seed'],$_POST['name'],$_POST['title'],$_POST['rss'],$_POST['url'],$_POST['twitter']);

	echo(json_encode("true"));
}

if(isset($_POST['delete_seed']))
{
	$seeds->delete_seed_info($_POST['delete_seed']);

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
	$seeds->add_seed($_POST["add_seed"]);

	echo(json_encode("true"));
}
?>
