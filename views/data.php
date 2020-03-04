<!-- Author: Drew Schineller-->
<?php $cache_data = Json_GetData_ByTitle("Data");
 ?>
<!-- About page-->
<!-- Heading image and title container-->
<div class="container header explore-header people-page">
    <div class="image-container search-page image-only">
    <img class="header-background contributors-page" src="<?php echo BASE_URL;?>assets/images/<?php echo $bg[$randIndex];?>" alt="Enslaved Background Image">
    <div class="container middlewrap">
        <h1><?php echo $cache_data['title'] ?></h1>
    </div>
    <div class="image-background-overlay"></div>
  </div>
</div>
<!-- info container-->
<div class="container info">
    <div class="container infowrap">
        <p><?php echo $cache_data['dataTitle'] ?></p>
    </div>
</div>
<div class="cardwrap contributors">
    <?php echo $cache_data['dataSplit'] ?>
</div>
