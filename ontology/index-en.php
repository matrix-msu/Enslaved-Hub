<!DOCTYPE html>
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<link rel="stylesheet" href="resources/primer.css" media="screen" />
<link rel="stylesheet" href="resources/rec.css" media="screen" />
<link rel="stylesheet" href="resources/extra.css" media="screen" />
<link rel="stylesheet" href="resources/owl.css" media="screen" />
<link rel="stylesheet" type="text/css" href="../assets/stylesheets/style.css" />
<title>Ontology Documentation generated by WIDOCO</title>


<!-- SCHEMA.ORG METADATA -->
<script type="application/ld+json">{"@context":"http://schema.org","@type":"TechArticle","url":"http://www.enslaved.org/1.0/","image":"http://vowl.visualdataweb.org/webvowl/#iri=http://www.enslaved.org/1.0/","name":"http://www.enslaved.org/1.0/", "headline":"Document describing the ontology http://www.enslaved.org/1.0/", "datePublished":"Wed Apr 10 09:46:12 EDT 2019"}</script>

<script language="JavaScript" type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.10.0/jquery.min.js"></script>
<script src="resources/marked.min.js"></script>

    <script>
function loadHash() {
  jQuery(".markdown").each(function(el){jQuery(this).after(marked(jQuery(this).text())).remove()});
	var hash = location.hash;
	if($(hash).offset()!=null){
	  $('html, body').animate({scrollTop: $(hash).offset().top}, 0);
}
	loadTOC();
}
function loadTOC(){
	//process toc dynamically
	  var t='<h2>Table of contents</h2><ul>';i = 1;j=0;
	  jQuery(".list").each(function(){
		if(jQuery(this).is('h2')){
			if(j>0){
				t+='</ul>';
				j=0;
			}
			t+= '<li>'+i+'. <a href=#'+ jQuery(this).attr('id')+'>'+ jQuery(this).ignore("span").text()+'</a></li>';
			i++;
		}
		if(jQuery(this).is('h3')){
			if(j==0){
				t+='<ul>';
			}
			j++;
			t+= '<li>'+(i-1)+'.'+j+'. '+'<a href=#'+ jQuery(this).attr('id')+'>'+ jQuery(this).ignore("span").text()+'</a></li>';
		}
	  });
	  t+='</ul>';
	  $("#toc").html(t);
}
 $.fn.ignore = function(sel){
        return this.clone().find(sel||">*").remove().end();
 };    $(function(){
      $("#abstract").load("sections/abstract-en.html");
      $("#introduction").load("sections/introduction-en.html");
      $("#overview").load("sections/overview-en.html");
      $("#description").load("sections/description-en.html");
      $("#references").load("sections/references-en.html");
      $("#crossref").load("sections/crossref-en.html", null, loadHash);
    });
    </script>
  </head>

<body>
  <?php require_once('../config.php') ?>
  <script language="JavaScript" type="text/javascript" src="../assets/javascripts/header.js"></script>
  <script type='text/javascript'>var BASE_URL = "<?php echo BASE_URL;?>"</script>
  <?php include '../header.php'; ?>

<div class="container header explore-header people-page">
    <div class="image-container search-page image-only">
    <img class="header-background contributors-page" src="<?php echo BASE_URL;?>assets/images/<?php echo $bg[$randIndex];?>" alt="Enslaved Background Image">
    <div class="container middlewrap">
        <h1>Ontology Specifications</h1>
    </div>
    <div class="image-background-overlay"></div>
    </div>
</div>

<div class="container ontology">

<div class="head">
<div style="float:right">language <a href="index-en.php"><b>en</b></a> </div>


<dl>
<dt>Download serialization:</dt><dd><span><a href="ontology.json" target="_blank" download><img src="https://img.shields.io/badge/Format-JSON_LD-blue.svg" alt="JSON-LD" /></a> </span><span><a href="ontology.xml" target="_blank" download><img src="https://img.shields.io/badge/Format-RDF/XML-blue.svg" alt="RDF/XML" /></a> </span><span><a href="ontology.nt" target="_blank" download><img src="https://img.shields.io/badge/Format-N_Triples-blue.svg" alt="N-Triples" /></a> </span><span><a href="ontology.ttl" target="_blank" download><img src="https://img.shields.io/badge/Format-TTL-blue.svg" alt="TTL" /></a> </span></dd><dt>License: </dt><dd><a href="https://creativecommons.org/licenses/by/4.0/" target="_blank"><img src="https://img.shields.io/badge/License-%20CC%20by%204.0-blue.svg" alt="https://creativecommons.org/licenses/by/4.0/" /></a>
</dd><dt>Visualization:</dt><dd><a href="webvowl/index.html#" target="_blank"><img src="https://img.shields.io/badge/Visualize_with-WebVowl-blue.svg" alt="Visualize with WebVowl" /></a></dd>
</dl>
</div>

<div id="abstract"></div>
<div id="toc"></div>     <div id="introduction"></div>
     <div id="overview"></div>
     <div id="description"></div>
     <div id="crossref"></div>
     <div id="references"></div>
<html>
<!-- <div id="acknowledgements">
<h2 id="ack" class="list">Acknowledgements <span class="backlink"> back to <a href="#toc">ToC</a></span></h2>
<p>
The authors would like to thank <a href="http://www.essepuntato.it/">Silvio Peroni</a> for developing <a href="http://www.essepuntato.it/lode">LODE</a>, a Live OWL Documentation Environment, which is used for representing the Cross Referencing Section of this document and <a href="https://w3id.org/people/dgarijo">Daniel Garijo</a> for developing <a href="https://github.com/dgarijo/Widoco">Widoco</a>, the program used to create the template used in this documentation.</p>
</div> -->

</html>

</div>
  <?php include '../footer.php'; ?>
</body>
</html>