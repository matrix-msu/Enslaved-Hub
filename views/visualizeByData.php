<!-- Author: Izzy Barraza-->
<!-- Visualize by data page-->
<?php $cache_data = Json_GetData_ByTitle("Visualize");
?>

<div class="container header visualizeByData-header">
  <div class="image-container visualizeByData-page image-only">
    <img class="header-background visualizeByData-page" src="<?php echo BASE_URL;?>assets/images/<?php echo $bg[$randIndex];?>" alt="Enslaved Background Image">
      <div class="container middlewrap">
        <h4 class="last-page-header"><a id="last-page" href="<?php echo BASE_URL;?>visualize"><span id="previous-title">Visualize / </span></a><span id="current-title">Visualize By Data</span></h4>
          <h1><?php echo "Visualize By Data" ?></h1>
    </div>
    <div class="image-background-overlay"></div>
  </div>
</div>

<div class="container info">
  <div class="search-section">
    <div class="inputwrap">
      <label for="chart_type">Chart Type</label>
      <select class="s2-single" id="chart-type" name="chart_type">
        <option value="pie">Pie</option>
        <option value="bar">Bar</option>
        <option value="line">Line</option>
      </select>
    </div>
    <div class="inputwrap">
      <label for="chart_field">Chart Field</label>
      <select class="s2-single" id="chart-field" name="chart_field">
        <option value="ages">Ages</option>
        <option value="agesBySex">Ages - Sex</option>
        <option value="15yo">Ages - 15 years old</option>
      </select>
    </div>
    <div class="inputwrap">
      <label for="chart_field">Project</label>
      <select class="s2-single" id="chart-project" name="chart_project">
        <?php foreach (projects as $type => $qid) { ?>
            <option value="<?php echo urlencode($type); ?>"><?php echo $type; ?></option>
        <?php } ?>
      </select>
    </div>
  </div>
  <div class="container datawrap">
    <iframe id="data-frame"></iframe>
  </div>
</div>
<script src="<?php echo BASE_URL;?>assets/javascripts/visualizeByData.js"></script>
