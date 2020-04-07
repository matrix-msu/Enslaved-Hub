<!-- Author: Drew Schineller-->
<!-- Our Partners page-->
<?php $cache_data = Json_GetData_ByTitle("Support Our Mission");
?>
<!-- Heading image and title container-->
<div class="container header">
    <div class="image-container search-page image-only">
	    <div class="container middlewrap">
        <div class="search-title">
        <h1><?php echo $cache_data['title'] ?></h1></div>
 </div>
      <div class="image-background-overlay"></div>
      <img class="header-background support-our-mission-page" src="<?php echo BASE_URL;?>assets/images/<?php echo $bg[$randIndex];?>" alt="Enslaved Background Image">

    </div>
</div>
<!-- info container-->
<div class="container info">
    <div class="container infowrap">
        <p><?php echo $cache_data['descr'] ?></p>
    </div>
</div>
