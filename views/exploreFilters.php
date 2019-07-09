<!-- Page author: Drew Schineller-->
<!-- Heading image and title container-->
<?php
//var_dump(EXPLORE_FORM);
//var_dump(EXPLORE_FILTER);
//var_dump($GLOBALS["FILTER_ARRAY"]);
$upperWithSpaces = ucwords(str_replace("_", " ", EXPLORE_FILTER));
?>

<div class="container header stories">
    <div class="container middlewrap">
        <h4 class="last-page-header"><a id="last-page" href="<?php echo BASE_URL;?>explore/<?php echo EXPLORE_FORM;?>"><span id="previous-title"><?php echo ucwords(EXPLORE_FORM);?> // </span></a><span id="current-title"><?php echo $upperWithSpaces; ?></span></h4>
        <h1><?php echo $upperWithSpaces; ?></h1>
    </div>
</div>
<!-- explore by -->
<div class="explore-by">
    <div class="sort-cards">
        <p>Sort <?php echo $upperWithSpaces; ?> By <img class="sort-arrow" src="<?php echo BASE_URL?>assets/images/Arrow2.svg" alt="sort cards button"/></p>
        <ul id="submenu" class="pagenum-menu">
            <li>Alphabetical (A-Z)</li>
            <li>Alphabetical (Z-A)</li>
            <li>Resources (Most to Least)</li>
            <li>Resources (Least to Most)</li>
        </ul>
    </div>
    <ul class="cards">
      <?php
      $lowerWithDashes = strtolower(str_replace(" ", "_", EXPLORE_FILTER));
      $typeCategories = array();
      if (array_key_exists($upperWithSpaces, $GLOBALS['FILTER_TO_FILE_MAP'])){

          $typeCategories = $GLOBALS['FILTER_TO_FILE_MAP'][$upperWithSpaces];
          // var_dump($typeCategories);
          ksort($typeCategories);
          // var_dump($typeCategories);
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
