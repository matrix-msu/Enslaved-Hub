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


<div class="container info">
<div class="container infowrap">
        <?php echo $cache_data['descr'] ?>
</div>
</div>
