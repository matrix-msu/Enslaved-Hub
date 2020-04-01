<!-- Author: Drew Schineller-->
<?php $cache_data = Json_GetData_ByTitle("About");
?>
<!-- <style>
  div{ about-header: url(<?php echo BASE_URL;?>assets/images/<?php echo $bg[$randIndex]?>); }
</style> -->
<!-- About page-->
<!-- Heading image and title container-->
<div class="container header about-header">
  <div class="image-container about-page image-only">
    <img class="header-background about-page" src="<?php echo BASE_URL;?>assets/images/<?php echo $bg[$randIndex];?>" alt="Enslaved Background Image">

      <div class="container middlewrap">
          <h1><?php echo $cache_data['title'] ?></h1>
    </div>
    <div class="image-background-overlay"></div>
  </div>
</div>

<!--
<!-- buttons -->
<div class="container info">
<div class="about-buttons">
    <div class="buttonwrap">
        <ul class="row">
            <li id="getinvolved">
                <a href="<?php echo BASE_URL?>getInvolved">
                    <div class="buttons">
                        <h3>Get Involved</h3>
                        <img class="about-button-chevron" id="green-chevron" src="<?php echo BASE_URL;?>assets/images/about-chevron-green.svg" alt="Chevron">
                        <img class="about-button-chevron" id="red-chevron" src="<?php echo BASE_URL;?>assets/images/about-chevron-red.svg" alt="Chevron">
                    </div>
                </a>
            </li>
            <li id="viewpartners">
                <a href="<?php echo BASE_URL?>ourPartners">
                    <div class="buttons">
                        <h3>Our Partners</h3>
                        <img class="about-button-chevron" id="green-chevron" src="<?php echo BASE_URL;?>assets/images/about-chevron-green.svg" alt="Chevron">
                        <img class="about-button-chevron" id="red-chevron" src="<?php echo BASE_URL;?>assets/images/about-chevron-red.svg" alt="Chevron">
                    </div>
                </a>
            </li>
            <li id="contactus">
                <a href="<?php echo BASE_URL?>contactUs">
                    <div class="buttons">
                        <h3>Contact Us</h3>
                        <img class="about-button-chevron" id="green-chevron" src="<?php echo BASE_URL;?>assets/images/about-chevron-green.svg" alt="Chevron">
                        <img class="about-button-chevron" id="red-chevron" src="<?php echo BASE_URL;?>assets/images/about-chevron-red.svg" alt="Chevron">
                    </div>
                </a>
            </li>
            <li id="ourteam">
                <a href="<?php echo BASE_URL?>ourTeam">
                    <div class="buttons">
                        <h3>Development Team</h3>
                        <img class="about-button-chevron" id="green-chevron" src="<?php echo BASE_URL;?>assets/images/about-chevron-green.svg" alt="Chevron">
                        <img class="about-button-chevron" id="red-chevron" src="<?php echo BASE_URL;?>assets/images/about-chevron-red.svg" alt="Chevron">
                    </div>
                </a>
            </li>
            <li id="references">
                <a href="<?php echo BASE_URL?>">
                    <div class="buttons">
                        <h3>See Our References</h3>
                        <img class="about-button-chevron" id="green-chevron" src="<?php echo BASE_URL;?>assets/images/about-chevron-green.svg" alt="Chevron">
                        <img class="about-button-chevron" id="red-chevron" src="<?php echo BASE_URL;?>assets/images/about-chevron-red.svg" alt="Chevron">
                    </div>
                </a>
            </li>
            <li id="webCrawler">
                <a href="<?php echo BASE_URL?>links">
                    <div class="buttons">
                        <h3>View Web Crawler</h3>
                        <img class="about-button-chevron" id="green-chevron" src="<?php echo BASE_URL;?>assets/images/about-chevron-green.svg" alt="Chevron">
                        <img class="about-button-chevron" id="red-chevron" src="<?php echo BASE_URL;?>assets/images/about-chevron-red.svg" alt="Chevron">
                    </div>
                </a>
            </li>
        </ul>
    </div>
</div>
-->
<!-- info container-->
        <p><?php echo $cache_data['descr'] ?></p>
</div>
