<!-- Page author: Drew Schineller-->
<!-- Heading image and title container-->
<?php
//var_dump(EXPLORE_FORM);
//var_dump($GLOBALS["FILTER_ARRAY"]);
$upper = ucfirst(EXPLORE_FORM);

// Get Title and Description from cache file
$cache_Data = Json_GetData_ByTitle($upper);
?>

<div class="container header explore-header about-header">
    <img class="header-background about-page" src="<?php echo BASE_URL;?>assets/images/<?php echo $bg[$randIndex];?>" alt="Enslaved Background Image">
    <div class="container middlewrap">
        <h1><?php echo $cache_Data['title'] ?></h1>
    </div>
</div>
<!-- info container-->
<div class="container info">
    <div class="container infowrap">
        <p> <?php echo $cache_Data['descr'] ?></p>
        <a href="<?php echo BASE_URL;?>search/<?php echo EXPLORE_FORM ?>" class="view-more">View All <?php echo $upper;?></a>
    </div>
</div>
<!-- explore by -->
<div class="container explore-by">
    <h1>Explore By</h1>
    <ul class="cards">
        <?php foreach ($GLOBALS["FILTER_ARRAY"][EXPLORE_FORM] as $type) {
            if($type == 'Source Type' || $type == 'Place Type'){
                if($type == 'Source Type'){
                    $explore_filter = 'source_type';
                }
                else{
                    $explore_filter = 'place_type';
                }
                $upperWithSpaces = ucwords(str_replace("_", " ", $explore_filter));
                $typeCategories = array();
                if (array_key_exists($type, $GLOBALS['FILTER_TO_FILE_MAP'])){

                    $typeCategories = $GLOBALS['FILTER_TO_FILE_MAP'][$type];
                    ksort($typeCategories);
                }
                foreach ($typeCategories as $category => $qid) {
                    ?>
                    <li class="hide-category">
                        <a href="<?php echo BASE_URL;?>search/<?php echo EXPLORE_FORM?>?<?php echo $explore_filter;?>=<?php echo $category;?>">
                            <p class='type-title'><?php echo $category;?></p>
                            <div id="arrow"></div><span id="<?php echo $category;?>">0</span>
                        </a>
                    </li>
                <?php }
            }
            else{
                // TODO::Not sure if we want this showing, will disable for now.
                if ($type != 'Modern Countries') {
            ?>
                    <li>
                        <a href="<?php echo BASE_URL?>explore/<?php echo EXPLORE_FORM.'/'.strtolower(str_replace(" ", "_", $type))?>">
                            <p class='type-title'><?php echo $type?></p>
                            <div id="arrow"></div>
                        </a>
                    </li>
            <?php }
            }
        }
        ?>
    </ul>
</div>
<!-- Featured People/Events -->
<?php if (in_array($upper, ['People', 'Events'])) { ?>
    <div class="card-slider explore-featured">
        <h2>Featured <?=$upper?></h2>
        <div class="cardwrap">
            <div class="cardwrap2">
                <ul class="cards-featured">
                </ul>
            </div>
        </div>
    </div>
<?php } ?>
<script src="<?php echo BASE_URL;?>assets/javascripts/explore.js"></script>
