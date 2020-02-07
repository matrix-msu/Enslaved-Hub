<!-- Page author: Drew Schineller-->
<!-- Heading image and title container-->
<?php
$upperWithSpaces = ucwords(str_replace("_", " ", EXPLORE_FILTER));

$bg = ['enslaved-header-bg.jpg','enslaved-header-bg2.jpg',
        'enslaved-header-bg3.jpg','enslaved-header-bg4.jpg',
        'enslaved-header-bg5.jpg','enslaved-header-bg6.jpg',
        'enslaved-header-bg7.jpg'];
$randIndex = array_rand($bg);
?>

<div class="container header stories">
    <img class="header-background exploreFilters-page" src="<?php echo BASE_URL;?>assets/images/<?php echo $bg[$randIndex];?>" alt="Enslaved Background Image">

    <div class="container middlewrap">

        <h4 class="last-page-header"><a id="last-page" href="<?php echo BASE_URL;?>explore/<?php echo EXPLORE_FORM;?>"><span id="previous-title"><?php echo ucwords(EXPLORE_FORM);?> // </span></a><span id="current-title"><?php echo $upperWithSpaces; ?></span></h4>
        <h1><?php echo $upperWithSpaces; ?></h1>
    </div>
</div>
<!-- explore by -->
<div class="explore-by">
    <ul class="cards">
      <?php
      $lowerWithDashes = strtolower(str_replace(" ", "_", EXPLORE_FILTER));
      $typeCategories = array();
      if (array_key_exists($upperWithSpaces, $GLOBALS['FILTER_TO_FILE_MAP'])){

          $typeCategories = $GLOBALS['FILTER_TO_FILE_MAP'][$upperWithSpaces];
          ksort($typeCategories);
      }

      foreach ($typeCategories as $category => $qid) { ?>
          <li class="hide-category">
              <a href="<?php echo BASE_URL;?>search/<?php echo EXPLORE_FORM?>?<?php echo EXPLORE_FILTER;?>=<?php echo $category;?>">
                  <p class='type-title'><?php echo $category;?></p>
                  <div id="arrow"></div><span id="<?php echo $category;?>">0</span>
              </a>
          </li>
      <?php } ?>
    </ul>
</div>

<script src="<?php echo BASE_URL;?>assets/javascripts/explore.js">
    var type = "<?php echo $upperWithSpaces ?>";
    var category = "<?php echo ucwords(EXPLORE_FORM) ?>";
</script>
