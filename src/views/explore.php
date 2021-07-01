<!-- Author: Drew Schineller-->
<!-- Main page-->

<?php
    $cache_data = Json_GetData_ByTitle("Explore");
    $counts = all_counts();
?>
<!-- Heading image and title container-->
<div class="container header explore-header people-page">
    <div class="image-container explore-page image-only">
      <div class="container middlewrap">
          <h1><?php echo $cache_data['title'] ?></h1>
      </div>
      <div class="image-background-overlay"></div>
  </div>
</div>
<main class="explore">
    <div class="section-wrap">
        <div class="section-info">
            <p><?php echo $cache_data['descr'] ?></p>
        </div>
        <div class="section-content">
            <a href="<?php echo BASE_URL;?>explore/people">
                <div class="content-link">
                    <img class="icon" src="<?php echo BASE_URL;?>/assets/images/Person-dark.svg" alt="person icon"/>
                    <h3>People</h3>
                    <span><?php echo $counts['people'];?></span>
                    <img class="arrow" src="<?php echo BASE_URL;?>/assets/images/Arrow3.svg" alt="link arrow"/>
                </div>
            </a>
            <a href="<?php echo BASE_URL;?>explore/events">
                <div class="content-link">
                    <img class="icon" src="<?php echo BASE_URL;?>/assets/images/Event-dark.svg" alt="event icon"/>
                    <h3>Events</h3>
                    <span><?php echo $counts['events'];?></span>
                    <img class="arrow" src="<?php echo BASE_URL;?>/assets/images/Arrow3.svg" alt="link arrow"/>
                </div>
            </a>
            <a href="<?php echo BASE_URL;?>explore/places">
                <div class="content-link">
                    <img class="icon" src="<?php echo BASE_URL;?>/assets/images/Place-dark.svg" alt="location icon"/>
                    <h3>Places</h3>
                    <span><?= $counts['places'] ?></span>
                    <img class="arrow" src="<?php echo BASE_URL;?>/assets/images/Arrow3.svg" alt="link arrow"/>
                </div>
            </a>
            <a href="<?php echo BASE_URL;?>explore/sources">
                <div class="content-link">
                    <img class="icon" src="<?php echo BASE_URL;?>/assets/images/Source-dark.svg" alt="source icon"/>
                    <h3>Sources</h3>
                    <span><?php echo $counts['sources'];?></span>
                    <img class="arrow" src="<?php echo BASE_URL;?>/assets/images/Arrow3.svg" alt="link arrow"/>
                </div>
            </a>
        </div>
    </div>
</main>
