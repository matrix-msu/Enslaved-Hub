<!-- Author: Drew Schineller-->
<!-- Our Partners page-->
<?php $cache_data = Json_GetData_ByTitle("Partner Projects");
$bg = ['enslaved-header-bg.jpg','enslaved-header-bg2.jpg',
        'enslaved-header-bg3.jpg','enslaved-header-bg4.jpg',
        'enslaved-header-bg5.jpg','enslaved-header-bg6.jpg',
        'enslaved-header-bg7.jpg'];
$randIndex = array_rand($bg);




 ?>
<!-- Heading image and title container-->
<div class="container header">
    <div class="image-container search-page image-only">
	    <div class="container middlewrap">
        <h4 class="last-page-header"><a id="last-page" href="<?php echo BASE_URL;?>about">
            <span id="previous-title">About // </span></a>
            <span id="current-title"><?php echo $cache_data['title'] ?></span>
        </h4>
        <div class="search-title">
        <h1><?php echo $cache_data['title'] ?></h1></div>
 </div>
      <div class="image-background-overlay"></div>
          <img class="header-background ourPartners-page" src="<?php echo BASE_URL;?>assets/images/<?php echo $bg[$randIndex];?>" alt="Enslaved Background Image">

    </div>
</div>
<!-- text sections -->
<div class="container partner-text">
      <p><?php echo $cache_data["descr"] ?></p>
</div>
