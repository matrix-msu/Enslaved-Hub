@extends('_layouts.main')

@section('body')

<?php
$bg = ['enslaved-header-bg.jpg','enslaved-header-bg2.jpg',
		'enslaved-header-bg3.jpg','enslaved-header-bg4.jpg',
		'enslaved-header-bg5.jpg','enslaved-header-bg6.jpg',
		'enslaved-header-bg7.jpg'];
$tempRand = array_rand($bg);

$options = array();
foreach(ALL_STORIES as $kid => $item){
	$option = $item['Sex'];
	if($option == "") continue;
	if(!isset($options[$option])){
		$options[$option] = 0;
	}
	$options[$option]++;
}
?>

<div class="container header stories">
    <div class="image-container exploreFilters-page image-only">
      <img class="header-background exploreFilters-page" src="<?php echo BASE_URL;?>assets/images/<?php echo $bg[$tempRand];?>" alt="Enslaved Background Image">

      <div class="container middlewrap">

          <h4 class="last-page-header"><a id="last-page" href="<?php echo BASE_URL;?>stories/"><span id="previous-title">Stories / </span></a><span id="current-title">Gender</span></h4>
          <h1>Gender</h1>
      </div>
      <div class="image-background-overlay"></div>
    </div>
</div>
<!-- explore by -->
<div class="explore-by">
    <ul class="cards">
      <?php
      foreach ($options as $option => $count) { ?>
          <li>
              <a href='<?php echo BASE_URL;?>stories/all/?filters={"Sex":["<?=$option?>"]}'>
                  <p class='type-title'><?=$option?></p>
                  <div id="arrow"></div><span id="<?=$option?>"><?=$count?></span>
              </a>
          </li>
      <?php } ?>
    </ul>
</div>

<script>
$(document).ready(function(){
	$('.cards li').click(function () {
		window.location = $(this).find("a").attr("href");
	});
});
</script>

@endsection
