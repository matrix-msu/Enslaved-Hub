<!-- Get Involved page-->
<?php $cache_data = Json_GetData_ByTitle("Get Involved");
?>
<!-- Heading image and title container-->
<div class="container header about-header getinvolved-page">
  <img class="header-background about-page" src="<?php echo BASE_URL;?>assets/images/<?php echo $bg[$randIndex];?>" alt="Enslaved Background Image">
    <div class="container middlewrap">
        <h4 class="last-page-header"><a id="last-page" href="<?php echo BASE_URL;?>about">
            <span id="previous-title">About / </span></a>
            <span id="current-title"><?php echo $cache_data['title'] ?></span>
        </h4>
        <h1><?php echo $cache_data['title'] ?></h1>
    </div>
</div>

<div class="container getinvolved-view">
  <a href="<?php echo BASE_URL?>">
      <div class="buttons">
          <h3>View Project & Scholar Directory</h3>
          <img class="about-button-chevron" id="green-chevron" src="<?php echo BASE_URL;?>assets/images/about-chevron-green.svg" alt="Chevron">
          <img class="about-button-chevron" id="red-chevron" src="<?php echo BASE_URL;?>assets/images/about-chevron-red.svg" alt="Chevron">
      </div>
  </a>
</div>

<!-- text sections -->
<div class="container getinvolved-submit">
    <h2>Submit Project or Scholar</h2>
    <?php echo $cache_data['descr'] ?>
    <a href="<?php echo BASE_URL?>projectSubmission">
        <div class="buttons" id="first-button">
            <h3>Project Submission</h3>
            <img class="about-button-chevron" id="green-chevron" src="<?php echo BASE_URL;?>assets/images/about-chevron-green.svg" alt="Chevron">
            <img class="about-button-chevron" id="red-chevron" src="<?php echo BASE_URL;?>assets/images/about-chevron-red.svg" alt="Chevron">
        </div>
    </a>
    <a href="<?php echo BASE_URL?>scholarSubmission">
        <div class="buttons">
            <h3>Scholar Submission</h3>
            <img class="about-button-chevron" id="green-chevron" src="<?php echo BASE_URL;?>assets/images/about-chevron-green.svg" alt="Chevron">
            <img class="about-button-chevron" id="red-chevron" src="<?php echo BASE_URL;?>assets/images/about-chevron-red.svg" alt="Chevron">
        </div>
    </a>
</div>
