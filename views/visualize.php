<!-- Author: Hyeungsuk Kim-->
<!-- Visualize page-->
<?php $cache_data = Json_GetData_ByTitle("Visualizations");
?>

<div class="container header visualize-header">
  <div class="image-container visualize-page image-only">
    <img class="header-background visualize-page" src="<?php echo BASE_URL;?>assets/images/<?php echo $bg[$randIndex];?>" alt="Enslaved Background Image">
      <div class="container middlewrap">
          <h1><?php echo $cache_data['title'] ?></h1>
    </div>
    <div class="image-background-overlay"></div>
  </div>
</div>

<div class="container info">
  <div class="container infowrap">
    <p><?php echo $cache_data['descr'] ?></p> 
  </div>
</div>

<div class="cardwrap visualize">
  <li class="card">
      <a href="<?php echo BASE_URL;?>visualizedata">
      <img src="<?php echo BASE_URL;?>assets/images/charts.svg" class="charts" alt="charts">
      <p>Charts</p>
      </a>
  </li>
  <li class="card">
    <img src="<?php echo BASE_URL;?>assets/images/maps.svg" class="maps" alt="maps">
    <p>Maps</p>
  </li>
  <li class="card">
    <img src="<?php echo BASE_URL;?>assets/images/timeline.svg" class="timeline" alt="timeline">
    <p>Timeline</p>
  </li>
</div>
