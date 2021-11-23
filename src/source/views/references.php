<!-- Get Involved page-->
<?php $cache_data = Json_GetData_ByTitle("References");
?>
<!-- Heading image and title container-->
<!-- <div class="container header">
    <div class="container middlewrap">
        <h1><?php echo $cache_data['title'] ?></h1>
        <img class="header-background home-page" src="<?php echo BASE_URL;?>assets/images/<?php echo $bg[$randIndex];?>" alt="Enslaved Background Image">
    </div>
</div> -->

<div class="container header references-header references-page">
    <div class="image-container references-page image-only">
      <img class="header-background references-page" src="<?php echo BASE_URL;?>assets/images/<?php echo $bg[$randIndex];?>" alt="Enslaved Background Image">
      <div class="container middlewrap">
          <h1><?php echo $cache_data['title'] ?></h1>
      </div>
      <div class="image-background-overlay"></div>
    </div>
</div>
<!-- info container-->
<div class="container info">
    <div class="container infowrap">
        <?php echo $cache_data['descr'] ?>
    </div>
</div>
    </div>
</div>
