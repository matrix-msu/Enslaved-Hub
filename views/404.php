<?php
$bg = ['enslaved-header-bg.jpg','enslaved-header-bg2.jpg',
        'enslaved-header-bg3.jpg','enslaved-header-bg4.jpg',
        'enslaved-header-bg5.jpg','enslaved-header-bg6.jpg',
        'enslaved-header-bg7.jpg'];
$randIndex = array_rand($bg);
?>

<div class="top">
 <img class="header-background 404-page" src="<?php echo BASE_URL;?>assets/images/<?php echo $bg[$randIndex];?>" alt="Enslaved Background Image">

  <div class="topwrap">
  </div>
</div>

<div class="middle-card">
  <div class="sort-middle-text1">
    <h>404 PAGE NOT FOUND</h><br>
  </div>
  <div class="sort-middle-text2">
    <h>Sorry, we couldn't find that page.</h><br>
  </div>
  <div class="sort-middle-text3">
    <h>Please try searching or visit the home page.</h><br>
  </div>

  <div class="button">
    <a href='https://robbie.dev.matrix.msu.edu/~kimiris/enslaved/'>Go Home</a>
  </div>
</div>

<div class="container page">
</div>
