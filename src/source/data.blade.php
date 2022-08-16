@extends('_layouts.main')

@section('body')

<?php
	$cache_data = Json_GetData_ByTitle("Data");

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

    <p>The following projects are among those that have linked data currently available within Enslaved.org. Filter the projects using the search tools below, or jump to more information on the projects within Enslaved.org.</p>

    <div class="data-search">
      <form>
        <label class="sr-only" for="data-search">Search for project by name or keyword</label>
        <input class="input-field data-search" type="search" id="data-search" name="data-search" placeholder="Search for project by name or keyword">

        <label class="sr-only" for="start-year">Start Year</label>
        <input class="input-field start-year" type="text" id="start-year" name="start-year" placeholder="Start Year">
        <p>To</p>

        <label class="sr-only" for="end-year">End Year</label>
        <input class="input-field end-year" type="text" id="end-year" name="end-year" placeholder="End Year">
        <button type="submit">Search</button>
      </form>
    </div>

  </div>

	<div class="container infowrap" id="projectData">

	</div>
    <div class="container infowrap">
        <?php echo $cache_data['descr'] ?>
    </div>
</div>

<script>
	var counts = String.raw`<?php echo file_get_contents('https://manta.matrix.msu.edu/msumatrix/public/exports/enslaved.org/visualizeCounts/projectData.json');?>`;
</script>
<script src="<?php echo BASE_URL;?>assets/javascripts/fuse.min.js"></script>
<script src="<?php echo BASE_URL;?>assets/javascripts/data.js"></script>

@endsection
