<!-- Page author: Drew Schineller-->
<!-- Heading image and title container-->
<?php
//var_dump(EXPLORE_FORM);
//var_dump($GLOBALS["FILTER_ARRAY"]);
$upper = ucfirst(EXPLORE_FORM);

// Get Title and Description from cache file
$cache_Data = Json_GetData_ByTitle($upper);
$bg = ['enslaved-header-bg.jpg','enslaved-header-bg2.jpg',
        'enslaved-header-bg3.jpg','enslaved-header-bg4.jpg',
        'enslaved-header-bg5.jpg','enslaved-header-bg6.jpg',
        'enslaved-header-bg7.jpg'];
$randIndex = array_rand($bg);
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
