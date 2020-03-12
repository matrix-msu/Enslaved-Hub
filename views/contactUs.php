<!-- Author: Drew Schineller-->
<!-- Contact Us page-->
<?php $cache_data = Json_GetData_ByTitle("Contact Us");
?>
<!-- Heading image and title container-->
<div class="container header about-header contactus-page">
    <div class="image-container search-page image-only">
       <img class="header-background contactUs-page" src="<?php echo BASE_URL;?>assets/images/<?php echo $bg[$randIndex];?>" alt="Enslaved Background Image">

       <div class="container middlewrap">
          <h4 class="last-page-header"><a id="last-page" href="<?php echo BASE_URL;?>about">
              <span id="previous-title">About // </span></a>
              <span id="current-title"><?php echo $cache_data['title'] ?></span>
          </h4>
          <h1><?php echo $cache_data['title'] ?></h1>
      </div>
      <div class="image-background-overlay"></div>
   </div>
</div>
<!-- info container-->
<p><?php echo $cache_data["descr"] ?></p>

    <div class="container contactUs-getinvolved button">
    <a href="<?php echo BASE_URL?>getInvolved">
        <div class="buttons">
            <h3>Get Involved</h3>
            <img class="about-button-chevron" id="green-chevron" src="<?php echo BASE_URL;?>assets/images/about-chevron-green.svg" alt="Chevron">
            <img class="about-button-chevron" id="red-chevron" src="<?php echo BASE_URL;?>assets/images/about-chevron-red.svg" alt="Chevron">
        </div>
    </a>
</div>
