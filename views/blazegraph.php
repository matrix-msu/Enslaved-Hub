<?php
//session_start();
//$_SESSION = array();
?>
<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" id="divi-style-css" href="style.css" type="text/css" media="all">
</head>
<div class="container main">
    <div class="container middlewrap">
        <h1>Blazegraph Test</h1>
    </div>
</div>

<div class="query-options">
    <h2 id="blaze-top">Show Results For</h2>
    <div><button class="people-query-btn">People</button></div>
<!--    <div><button class="places-query-btn">Places</button></div>-->
    <div><button class="events-query-btn">Events</button></div>
<!--    <div><button class="sources-query-btn">Sources</button></div>-->
<!--    <div><button class="projects-query-btn">Projects</button></div>-->
</div>


<main class="blazegraph-records">

</main>






<br><br>
<form action="api.php" style="text-align:center;margin-top:50px">
    <label for="textarea" class="sr-only">text area</label>
    <textarea  name="textarea" id="textarea" cols="80" rows="20"></textarea>
    <br>
    <button id="submit" type="submit">Submit</button>
</form>
<h2 class="nav-section"><a name="nav-places">Results:</a></h2>
<div id="results"></div>
<h2 class="nav-section"><a name="nav-places">Previous queries:</a></h2>

<?php
$path = "functions/queries.json";
$contents = file_get_contents($path);

foreach(json_decode($contents, true) as $key => $query ){
    echo $key . '   <button class="delete" data-id="'.$key.'">delete</button>   :  <p class="previous-query">'. $query . '</p><br><br>';
}
?>

<script src="<?php echo BASE_JS_URL;?>blazegraph.js"></script>
