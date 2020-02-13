<!-- Get Involved page-->
<?php $cache_data = Json_GetData_ByTitle("Development Team");
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
        <h1><?php echo $cache_data['title'] ?></h1>
        </div>
	    </div>
	     <div class="image-background-overlay"></div>
      <img class="header-background ourPartners-page" src="<?php echo BASE_URL;?>assets/images/<?php echo $bg[$randIndex];?>" alt="Enslaved Background Image">
    </div>
</div>
<!-- info container-->
<div class="container partner-text">
<p><?php echo $cache_data["descr"] ?></p>
</div>
