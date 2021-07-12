@extends('_layouts.main')

@section('body')

<?php
include_once( BASE_LIB_PATH . "koraSearchRemote.php" );

// Get all Stories using KORA_Search
$fields =  ['Title', 'Featured', 'Images'];
$clause = new KORA_Clause("Display", "=", "True");
$stories = KORA_Search(TOKEN, PID, STORY_SID, $clause, $fields);
unset($stories["count"]);

$fileName= "./source/assets/javascripts/searchData.js";
$script = "var allStoriesRecords = JSON.parse('".addslashes(json_encode(array_values($stories), true))."');";
file_put_contents($fileName, $script);
$gzScript = gzencode($script);
file_put_contents($fileName.'.gz', $gzScript);

// Gettting featured records
$clause = new KORA_Clause("Display", "=", "True");
$clause = new KORA_Clause($clause, "AND", new KORA_Clause("Featured", "=", "TRUE"));
$sort = array(array("field" => 'Title', "direction" => SORT_ASC ));
$featured = KORA_Search(TOKEN, PID, STORY_SID, $clause, $fields, $sort);
unset($featured["count"]);

// Get Title and Description from cache file
$cache_Data = Json_GetData_ByTitle("Stories");
?>
<!-- Stories page-->
<!-- Heading image and title container-->
<div class="container header stories">
  <div class="image-container stories-page image-only">
    <img class="header-background stories-page" src="<?php echo BASE_URL;?>assets/images/<?php echo $GLOBALS['bg'][$GLOBALS['randIndex']];?>" alt="Enslaved Background Image">
      <div class="container middlewrap">
          <h1><?php echo $cache_Data['title'] ?></h1>
      </div>
      <div class="image-background-overlay"></div>
  </div>
</div>
<!-- info container-->
<div class="container info">
    <div class="container infowrap">
        <p><?php echo $cache_Data['descr'] ?></p>
    </div>
</div>
<!-- featured stories container-->
<div class="card-slider featured-stories">
    <h2>Featured Stories</h2>
    <div class="cardwrap">
        <div class="cardwrap2">
            <ul class="cards">
                <?php
                $bg = ['enslaved-header-bg.jpg','enslaved-header-bg2.jpg',
                        'enslaved-header-bg3.jpg','enslaved-header-bg4.jpg',
                        'enslaved-header-bg5.jpg','enslaved-header-bg6.jpg',
                        'enslaved-header-bg7.jpg'];

                // print_r($featured);die;
                foreach ($featured as $kid => $story) {
                    //get images from records
                    if (!empty($story["Images"]["localName"])){
                        $storyImage = $story["Images"]["localName"];
                    } else {
                        $storyIndex = array_rand($bg);
                        $storyImage = BASE_URL.'assets/images/'.$bg[$storyIndex];
                    }

                    echo '<li class="card card-story" style="background-image: url('.$storyImage.')">';
                    echo '<a href="'.BASE_URL.'fullStory?kid='.$kid.'">';
                    echo '<h2 class="card-title">'.$story['Title'].'</h2>';
                    echo '</a><div class="overlay"></div></li>';
                }
                ?>
            </ul>
        </div>
    </div>
    <div class="controls">
        <div class="arrows">
            <div class="prev"><img src="<?php echo BASE_IMAGE_URL?>chevron.svg" alt="arrow"></div>
            <div class="next"><img src="<?php echo BASE_IMAGE_URL?>chevron.svg" alt="arrow"></div>
        </div>
        <div class="dots">
        </div>
    </div>
</div>
<!-- all stories container-->
<div id="all-header-scroll" class="container card-column storycard">
    <div id="all-header" class="container cardheader-wrap">
        <h2 class="column-header">All Stories</h2>
        <div class="sort-search">
          <div class="container pagiwrap">
              <div class="sort-pages">
                  <p><span class="per-page-text"><?= (isset($_GET['count']) ? $_GET['count'] : '10') ?></span> Per Page <img class="sort-arrow" src="<?php echo BASE_URL?>assets/images/chevron.svg" alt="sort stories button"/></p>
                  <ul id="submenu" class="pagenum-menu">
                      <li class="count-option" data-count="10"><span>10</span> Per Page</li>
                      <li class="count-option" data-count="20"><span>20</span> Per Page</li>
                      <li class="count-option" data-count="50"><span>50</span> Per Page</li>
                  </ul>
              </div>
            </div>
            <div class="container sort-stories">
                <?php
                $sort_text = "Sort By";
                if (isset($_GET['field']) && isset($_GET['direction'])) {
                    if ($_GET['field'] == 'title') {
                        if ($_GET['direction'] == 'asc') {
                            $sort_text = "Alphabetical (A-Z)";
                        } else {
                            $sort_text = "Alphabetical (Z-A)";
                        }
                    } else {
                        // Date
                        if ($_GET['direction'] == 'asc') {
                            $sort_text = "Date (Newest First)";
                        } else {
                            $sort_text = "Date (Oldest First)";
                        }
                    }
                }
                ?>
                <span class="sort-stories-text"><span style="white-space: nowrap;"> <?= $sort_text; ?> </span><img class="sort-arrow" src="<?php echo BASE_URL?>assets/images/chevron.svg" alt="sort stories button"></span>
                <ul id="submenu" class="sorting-menu">
                    <li class="sort-option" data-field="title" data-direction="asc">Alphabetically (A-Z)</li>
                    <li class="sort-option" data-field="title" data-direction="desc">Alphabetically (Z-A)</li>
                    <li class="sort-option" data-field="start date" data-direction="asc">Date (Newest First)</li>
                    <li class="sort-option" data-field="start date" data-direction="desc">Date (Oldest First)</li>
                </ul>
            </div>
          </div>
          <div class="sort-search">
            <div class="container search">
                <form action="" method="get">
                    <label for="searchbar" class="sr-only">searchbar</label>
                    <input id="searchbar" class="search-field" type="text" name="searchbar" placeholder="Find a Story By Title or Keyword"/>
                    <button class="search-icon-2" type="submit"><img src="<?php echo BASE_URL;?>/assets/images/Search-dark.svg" alt="search-icon"></button>
                </form>
            </div>
        </div>
    </div>
    <div class="container card-wrap" id="allStories">
        <ul class="card-row cards" id='AllStoriesContainer'>
        </ul>
    </div>
    <div class="container pagiwrap">
        <div class="sort-pages">
        </div>
        <div id="pagination" style="margin:initial;">
            <input class="current-page" type="hidden" value="1">
            <div class="first-prev">
                <div class="pagi-first" value="1"><p>First</p></div>
                <div class="pagi-left"><img src="<?php echo BASE_IMAGE_URL; ?>chevron.svg" alt="Arrow Left"/></div>
            </div>
            <div class="page-numbers">
                <span class="dotsLeft">...</span>
                <span class="num one"></span>
                <span class="num two"></span>
                <span class="num three"></span>
                <span class="num four"></span>
                <span class="num five"></span>
                <span class="dotsRight">...</span>
            </div>
            <div class="last-next">
                <div class="pagi-right"><img src="<?php echo BASE_IMAGE_URL; ?>chevron.svg" alt="Arrow Right"/></div>
                <div class="pagi-last" value="1"><p>Last</p></div>
            </div>
        </div>
    </div>
</div>

<script src="<?php echo BASE_URL;?>assets/javascripts/fuse.min.js"></script>
<script src="<?php echo BASE_URL;?>assets/javascripts/cardSlider.js"></script>
<script src="<?php echo BASE_URL;?>assets/javascripts/pagination.js"></script>
<script src="<?php echo BASE_URL;?>assets/javascripts/stories.js"></script>
<script src="<?php echo BASE_URL;?>assets/javascripts/searchData.js"></script>
<script>
	const storiesOptions = {
	  includeScore: true,
	  useExtendedSearch: true,
	  keys: ['Title', 'Text']
	};
	const storiesFuse = new Fuse(allStoriesRecords, storiesOptions);
</script>


@endsection
