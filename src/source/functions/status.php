<?php

function getUrl($url){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	$output = curl_exec($ch);
	curl_close($ch);
	return json_decode($output, true);
}

function status(){
	$liveBaseUrl = "https://enslaved.org/";
	$baseUrl = BASE_URL;
	if($baseUrl != "https://enslaved.org"){
		$baseUrl = $liveBaseUrl;
	}
	$url1 = $baseUrl . "api/getFullRecordHtml?QID=Q125&type=person";
	$url2 = $baseUrl . "api/keywordSearch?preset=all&sort_field=label.sort&limit=20&offset=0";

	$working = true;

	$content = getUrl($url1);
	if(
		!isset($content['header']) ||
		!isset($content['description']) ||
		!isset($content['details'])
	){
		$working = false;
	}

	$content = getUrl($url2);
	if(
		!isset($content['total'])
	){
		$working = false;
	}

	if($working){
		return "Both <br>\n$url1<br>\nand<br>\n$url2<br>\nare working";
	}else{
		http_response_code(500);
		return "Somthing isn't working with <br>\n$url1<br>\nor<br>\n$url2";
	}
}
