<?php
    $cache_data = Json_GetData_ByTitle("Visualize");
?>
<style>
    body {
        /* disabling horizontal body scrolling for the visualize page */
        overflow-x: hidden;
    }
</style>
<div class="container header visualizeByData-header">
  <div class="image-container visualizeByData-page image-only">
    <img class="header-background visualizeByData-page" src="<?php echo BASE_URL;?>assets/images/<?php echo $bg[$randIndex];?>" alt="Enslaved Background Image">
      <div class="container middlewrap">
        <h4 class="last-page-header"><a id="last-page" href="<?php echo BASE_URL;?>visualize"><span id="previous-title">Visualize / </span></a><span id="current-title">Visualize Data</span></h4>
          <h1><?php echo "Visualize Data" ?></h1>
    </div>
    <div class="image-background-overlay"></div>
  </div>
</div>

<div class="container info">
  <div class="search-section">
    <div class="inputwrap">
      <label for="chart_type">Chart Type</label>
      <select class="s2-single" id="chart-type" name="chart_type">
        <option value="Dashboard">Dashboard</option>
        <option value="BarChart">Bar</option>
        <option value="PieChart">Pie</option>
        <option value="Table">Table</option>
        <!-- <option value="line">Line</option> -->
      </select>
    </div>
    <div class="inputwrap">
      <label for="chart_field">Chart Field</label>
      <select class="s2-single" id="chart-field" name="chart_field">
        <option value="po">Project Overview</option>
        <option value="ef">Enslaved Female Records</option>
        <option value="ec">Enslaved Child Records</option>
        <option value="em">Enslaved Male Records</option>
        <option value="mf">Master Female Records</option>
        <option value="f">Female Records</option>
        <option value="ethno">Ethnodescriptor Records</option>
      </select>
    </div>
    <div class="inputwrap">
      <label for="chart_project">Project</label>
      <select class="s2-single" id="chart-project" name="chart_project">
          <option value="All Projects">All Projects</option>
        <?php foreach (projects as $type => $qid) { ?>
            <option value="<?php echo $type; ?>"><?php echo $type; ?></option>
        <?php } ?>
      </select>
      <div><a id="search-records-link" href="">Search Project Records <img src="<?php echo BASE_URL;?>assets/images/about-chevron-red.svg" alt="Chevron Right"/></a></div>
    </div>
  </div>
  <div class="container datawrap">
  </div>
  <p class="container info infowrap">Learn more about the <a href="https://docs.enslaved.org/controlledVocabulary/" target="_blank"/>Enslaved Controlled Vocabulary</a></p>
</div>
<script type="text/javascript">
    var counts = String.raw`<?php echo file_get_contents('https://manta.matrix.msu.edu/msumatrix/public/exports/enslaved.org/visualizeCounts/counts.json');?>`;
    counts = JSON.parse(counts);
    var config = `<?php echo file_get_contents('./visualizeCounts/config.json');?>`;
    config = JSON.parse(config);
</script>
<script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
<script src="<?php echo BASE_URL;?>assets/javascripts/visualizeByData.js"></script>
