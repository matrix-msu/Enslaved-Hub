<!-- Page author: Drew Schineller-->
<?php
include_once( BASE_LIB_PATH . "koraSearchRemote.php" );

// Pagination Vars
$sortField = (isset($_GET['field']) ? ucwords($_GET['field']) : 'Title');
$sortDirection = (isset($_GET['direction']) ? strtoupper($_GET['direction']) : 'ASC');
$storiesPerPage = (isset($_GET['count']) && is_numeric($_GET['count']) ? $_GET['count'] : '8');
$page = (isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : '1');

// Making sure search keyword(s) are provided
$searchString = (!empty($_GET['searchbar']) ? htmlspecialchars($_GET['searchbar']) : "");
$keywords = !empty($searchString) ? explode(" ",$searchString) : [];

// Getting Stories using korawrappper
$fields =  ['Title', 'Featured', 'Images'];
// $sort = [ [$sortField => $sortDirection] ];
$startIndex = ($page - 1) * $storiesPerPage;
// $koraResults = koraWrapperSearch(STORY_SID, $fields,
//  array("Display"), "TRUE", $sort, $startIndex,
//  $storiesPerPage, array("size" => true)
// );

// $stories = json_decode($koraResults, true);
// echo "<script>console.log(".json_encode($stories).")</script>";


$clause = new KORA_Clause("Display", "=", "True");
if(!empty($keywords)) {
  $other_clause;
  foreach ($keywords as $keyword) {
    if(empty($other_clause)) $other_clause = new KORA_Clause("Title", "LIKE", $keyword);
    else $other_clause = new KORA_Clause($other_clause, "OR" ,new KORA_Clause("Title", "LIKE", $keyword));
  }
  $clause = new KORA_Clause($clause, "AND", $other_clause);
}
$sort = array(array("field" => $sortField, "direction" => $sortDirection == "ASC" ? SORT_ASC : SORT_DESC));
$stories = KORA_Search(TOKEN, PID, STORY_SID, $clause, $fields, $sort, $startIndex, $storiesPerPage);

// echo "<script>console.log(".json_encode($stories).")</script>";

$count = $stories["count"]; // Total count of stories
unset($stories["count"]);

// echo "<script>console.log(".json_encode($count).")</script>";

$featured = [];
// foreach ($stories['records'][0] as $kid => $story) {
foreach ($stories as $kid => $story) {
    if (isset($story['Featured']) && $story['Featured'] == 'TRUE') {
        $featured[$kid] = $story;
    }
}
$page_count = ceil($count / $storiesPerPage);
if ($page < 1) {
    $page == 1;
} elseif ($page > $page_count) {
    $page = $page_count;
}


// Get Title and Description from cache file
$cache_Data = Json_GetData_ByTitle("Stories");
?>
<!-- Stories page-->
<!-- Heading image and title container-->
<div class="container header stories">
  <div class="image-container stories-page image-only">
    <img class="header-background stories-page" src="<?php echo BASE_URL;?>assets/images/<?php echo $bg[$randIndex];?>" alt="Enslaved Background Image">
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
<div class="container card-column storycard">
    <div id="all-header" class="container cardheader-wrap">
        <h2 class="column-header">All Stories</h2>
        <div class="sort-search">
            <div class="container sort-stories">
                <?php
                $sort_text = "Sort Stories By";
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
                <span class="sort-stories-text"><?= $sort_text; ?> <img class="sort-arrow" src="<?php echo BASE_URL?>assets/images/chevron.svg" alt="sort stories button"></span>
                <ul id="submenu" class="sorting-menu">
                    <li class="sort-option" data-field="title" data-direction="asc">Alphabetically (A-Z)</li>
                    <li class="sort-option" data-field="title" data-direction="desc">Alphabetically (Z-A)</li>
                    <li class="sort-option" data-field="start date" data-direction="desc">Date (Newest First)</li>
                    <li class="sort-option" data-field="start date" data-direction="asc">Date (Oldest First)</li>
                </ul>
            </div>
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
            <?php
            displayStories($stories);
            ?>
        </ul>
    </div>
    <div class="container pagiwrap">
        <div class="sort-pages">
            <p><span class="per-page-text"><?= (isset($_GET['count']) ? $_GET['count'] : '8') ?></span> Per Page <img class="sort-arrow" src="<?php echo BASE_URL?>assets/images/chevron.svg" alt="sort stories button"/></p>
            <ul id="submenu" class="pagenum-menu">
                <li class="count-option" data-count="8"><span>8</span> Per Page</li>
                <li class="count-option" data-count="12"><span>12</span> Per Page</li>
                <li class="count-option" data-count="16"><span>16</span> Per Page</li>
                <li class="count-option" data-count="20"><span>20</span> Per Page</li>
            </ul>
        </div>

        <div class="pagination-container">
            <div class="pagination-prev btn-prev no-select" data-page="<?php echo ($page > 0 ? $page - 1 : ''); ?>">
                <img class="chevron" src="<?php echo BASE_URL;?>assets/images/chevron.svg" alt="Previous Featured Biography">
            </div>

            <ul class="page-select">
                <?php
                $pag_html = '';
                if ($page > 3) {
                    $pag_html .= '<li data-page="1">1</li>';
                }

                if ($page > 4) {
                    $pag_html .= '<li>...</li>';
                }

                if ($page == $page_count && $page - 4 > 1) {
                    $pag_html .= '<li data-page="'.($page - 4).'">'.($page - 4).'</li>';
                }

                if ($page >= $page_count - 1 && $page - 3 > 1) {
                    $pag_html .= '<li data-page="'.($page - 3).'">'.($page - 3).'</li>';
                }

                if ($page - 2 >= 1) {
                    $pag_html .= '<li data-page="'.($page - 2).'">'.($page - 2).'</li>';
                }

                if ($page - 1 >= 1) {
                    $pag_html .= '<li data-page="'.($page - 1).'">'.($page - 1).'</li>';
                }

                $pag_html .=  '<li class="active">'.$page.'</li>';

                if ($page + 1 <= $page_count) {
                    $pag_html .= '<li data-page="'.($page + 1).'">'.($page + 1).'</li>';
                }

                if ($page + 2 <= $page_count) {
                    $pag_html .= '<li data-page="'.($page + 2).'">'.($page + 2).'</li>';
                }

                if ($page <= 2 && $page + 3 < $page_count) {
                    $pag_html .= '<li data-page="'.($page + 3).'">'.($page + 3).'</li>';
                }

                if ($page == 1 && $page + 4 < $page_count) {
                    $pag_html .= '<li data-page="'.($page + 4).'">'.($page + 4).'</li>';
                }

                if ($page_count - $page > 3) {
                    $pag_html .= '<li>...</li>';
                }

                if ($page_count - $page >= 3) {
                    $pag_html .= '<li data-page="'.$page_count.'">'.$page_count.'</li>';
                }

                echo $pag_html;
                ?>
            </ul>

            <div class="pagination-next btn-next no-select" data-page="<?php echo ($page < $page_count ? $page + 1 : ''); ?>">
                <img class="chevron" src="<?php echo BASE_URL;?>assets/images/chevron-light.svg" alt="Next Featured Biography">
            </div>
        </div>
    </div>
</div>

<script src="<?php echo BASE_URL;?>assets/javascripts/cardSlider.js"></script>
<script src="<?php echo BASE_URL;?>assets/javascripts/stories.js"></script>
<script src="<?php echo BASE_URL;?>assets/javascripts/storyPagination.js"></script>
