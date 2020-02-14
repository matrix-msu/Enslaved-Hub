<!-- Author: Hyeungsuk Kim-->
<!-- Visualize page-->
<?php $cache_data = Json_GetData_ByTitle("Visualize");
?>

<div class="container header visualize-header">
  <img class="header-background visualize-page" src="<?php echo BASE_URL;?>assets/images/<?php echo $bg[$randIndex];?>" alt="Enslaved Background Image">

    <div class="container middlewrap">
        <h1><?php echo $cache_data['title'] ?></h1>
  </div>

</div>

<div class="container info">
  <div class="container infowrap">
    <p>Brief info on Section. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  Lorem ipsum dolor tempor aliqua  consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna Lorem ipsum  consectetur a tempor incididunt ut labore et dolore magna Lorem ipsum dolor tempor aliqua  consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna Lorem ipsum  consectetur  Lorem ipsum.</p>
  </div>
</div>

<div class="cardwrap visualize">
  <li class="card">
    <img src="assets/images/ByData.svg" class="byData" alt="byData">
    <p>By Data</p>
  </li>
  <li class="card">
    <img src="assets/images/BySpace.svg" class="bySpace" alt="bySpace">
    <p>By Space</p>
  </li>
  <li class="card">
    <img src="assets/images/ByTime.svg" class="byTime" alt="byTime">
    <p>By Time</p>
  </li>

</div>
