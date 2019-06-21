<!-- Page author: Drew Schineller-->
<?php

// Pagination Vars
$sortField = (isset($_GET['field']) ? ucwords($_GET['field']) : 'Title');
$sortDirection = (isset($_GET['direction']) ? strtoupper($_GET['direction']) : 'ASC');
$storiesPerPage = (isset($_GET['count']) && is_numeric($_GET['count']) ? $_GET['count'] : '8');
$page = (isset($_GET['page']) && is_numeric($_GET['page']) ? $_GET['page'] : '1');

$stories = getStories($page, $storiesPerPage, [$sortField, $sortDirection]);

$count = $stories["counts"]["global"]; // Total count of stories

$featured = [];
foreach ($stories['records'][0] as $kid => $story) {
    if (isset($story['Featured']) && $story['Featured']['value'] == 'TRUE') {
        $featured[$kid] = $story;
    }
}

$page_count = ceil($count / $storiesPerPage);
if ($page < 1) {
    $page == 1;
} elseif ($page > $page_count) {
    $page = $page_count;
}


// Dynamically pull data from cache file (webPages.json)
$cached_data = file_get_contents(BASE_PATH . "/wikiconstants/webPages.json");
// echo '<script>console.log('.$cached_data.')</script>';
$cached_data = json_decode($cached_data, true); // Convert the json string to a php array

$title = "Stories";
$description = "";
foreach ($cached_data as $content) {
    if(array_key_exists("SubNavigation Display", $content) && $content["SubNavigation Display"]["value"] == "FALSE") continue;
    if(array_key_exists("Title", $content) && $content["Title"]["value"] == "Stories")
    {
        $title = $content["Title"]["value"];
        $description = $content["Description"]["value"];
        break;
    }
}
?>
<!-- Stories page-->
<!-- Heading image and title container-->
<div class="container header stories">
    <div class="container middlewrap">
        <h1><?php echo $title; ?></h1>
    </div>
</div>
<!-- info container-->
<div class="container info">
    <div class="container infowrap">
        <p><?php echo $description; ?></p>
    </div>
</div>
<!-- featured stories container-->
<div class="container column storycard">
    <div class="container cardheader-wrap">
        <h2 class="column-header">Featured Stories</h2>
    </div>
    <div class="container cardwrap" id="featured">
        <ul class="row">
            <?php
            foreach ($featured as $kid => $story) {
                echo '<li><a href="'.BASE_URL.'fullStory?kid='.$kid.'">';
                echo '<div class="container cards">';
                echo '<h2 class="card-title">'.$story['Title']['value'].'</h2>';
                echo '<h3 class="card-view-story">View Story <div class="view-arrow"></div></h3>';
                echo '</div></a></li>';
            }
            ?>
            <!-- <li>
                <a href="<?php echo BASE_URL?>fullStory">
                    <div class="container cards">
                        <p class="card-title">Title of Featured Story Goes Here Like This</p>
                        <h4 class="card-view-story">View Story <div class="view-arrow"></div></h4>
                    </div>
                </a>
            </li> -->
        </ul>
    </div>
</div>
<!-- all stories container-->
<div class="container column storycard">
    <div id="all-header" class="container cardheader-wrap">
        <div class="container header-search">
            <h2 class="column-header">All Stories</h2>
            <div class="container search">
                <form action="submit">
                    <label for="searchbar" class="sr-only">searchbar</label>
                    <input id="searchbar" class="search-field" type="text" name="searchbar" placeholder="Find a Story By Title or Keyword"/>
                    <button class="search-icon-2" type="submit"><img src="<?php echo BASE_URL;?>/assets/images/Search-dark.svg" alt="search-icon"></button>
                </form>
            </div>
        </div>
    </div>
    <div class="container cardwrap" id="allStories">
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
            <span class="sort-stories-text"><?= $sort_text; ?> <img class="sort-arrow" src="<?php echo BASE_URL?>assets/images/Arrow2.svg" alt="sort stories button"></span>
            <ul id="submenu" class="sorting-menu">
                <li class="sort-option" data-field="title" data-direction="asc">Alphabetically (A-Z)</li>
                <li class="sort-option" data-field="title" data-direction="desc">Alphabetically (Z-A)</li>
                <li class="sort-option" data-field="start date" data-direction="desc">Date (Newest First)</li>
                <li class="sort-option" data-field="start date" data-direction="asc">Date (Oldest First)</li>
            </ul>
        </div>
        <ul class="row" id='AllStoriesContainer'>
            <?php
            displayStories($stories);

            ?>
        </ul>
    </div>
    <div class="container pagiwrap">
        <div class="sort-pages">
            <p><span class="per-page-text"><?= (isset($_GET['count']) ? $_GET['count'] : '8') ?></span> Per Page <img class="sort-arrow" src="<?php echo BASE_URL?>assets/images/Arrow2.svg" alt="sort stories button"/></p>
            <ul id="submenu" class="pagenum-menu">
                <li class="count-option" data-count="8"><span>8</span> Per Page</li>
                <li class="count-option" data-count="12"><span>12</span> Per Page</li>
                <li class="count-option" data-count="16"><span>16</span> Per Page</li>
                <li class="count-option" data-count="20"><span>20</span> Per Page</li>
            </ul>
        </div>

        <div class="pagination-container">
            <div class="pagination-prev btn-prev no-select" data-page="<?php echo ($page > 0 ? $page - 1 : ''); ?>">
                <img class="chevron" src="<?php echo BASE_URL;?>assets/images/Arrow3.svg" alt="Previous Featured Biography">
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

                if ($page == $page_count && $page - 4 > 0) {
                    $pag_html .= '<li data-page="'.($page - 4).'">'.($page - 4).'</li>';
                }

                if ($page >= $page_count - 1 && $page - 3 > 0) {
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

                if ($page <= 2 && $page + 3 <= $page_count) {
                    $pag_html .= '<li data-page="'.($page + 3).'">'.($page + 3).'</li>';
                }

                if ($page == 1 && $page + 4 <= $page_count) {
                    $pag_html .= '<li data-page="'.($page + 4).'">'.($page + 4).'</li>';
                }

                if ($page_count - $page > 4) {
                    $pag_html .= '<li>...</li>';
                }

                if ($page_count - $page > 3) {
                    $pag_html .= '<li data-page="'.$page_count.'">'.$page_count.'</li>';
                }

                echo $pag_html;
                ?>
            </ul>

            <div class="pagination-next btn-next no-select" data-page="<?php echo ($page < $page_count ? $page + 1 : ''); ?>">
                <img class="chevron" src="<?php echo BASE_URL;?>assets/images/Arrow3.svg" alt="Next Featured Biography">
            </div>
        </div>
    </div>
</div>

<script src="<?php echo BASE_URL;?>assets/javascripts/stories.js"></script>
<script src="<?php echo BASE_URL;?>assets/javascripts/storyPagination.js"></script>
