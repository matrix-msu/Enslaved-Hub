<!-- Page author: Drew Schineller-->
<!-- Heading image and title container-->
<?php
if (isset($_GET['type'])){
    $type = $_GET['type'];
} else {
    $type = '';
}
?>



<div class="container header stories">
    <div class="container middlewrap">
        <h4 class="last-page-header"><a id="last-page" href="<?php echo BASE_URL;?>exploreEvents/"><span id="previous-title">Events // </span></a><span id="current-title"><?php echo $type;?></span></h4>
        <p class="events-heading"><?php echo $type;?></p>
    </div>
</div>
<!-- info container-->
<div class="container info">
    <div class="container infowrap">
        <p>Brief info on Section. Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod  Lorem ipsum dolor tempor aliqua  consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna Lorem ipsum  consectetur a tempor incididunt ut labore et dolore magna Lorem ipsum dolor tempor aliqua  consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna Lorem ipsum  consectetur.</p>
    </div>
</div>
<!-- explore by -->
<div class="explore-by">
    <div class="sort-cards">
        <p>Sort Genders By <img class="sort-arrow" src="<?php echo BASE_URL;?>assets/images/Arrow2.svg" alt="sort cards button"/></p>
        <ul id="submenu" class="pagenum-menu">
            <li>Alphabetical (A-Z)</li>
            <li>Alphabetical (Z-A)</li>
            <li>Resources (Most to Least)</li>
            <li>Resources (Least to Most)</li>
        </ul>
    </div>
    <ul class="cards">
        <?php
        $typeCategories = array();
        $typeID = str_replace(' ', '-', $type) . '-';
        $typeLabel = '';


        switch ($type){
            case "Event Type":
                $typeCategories = eventTypes;
                $typeLabel = 'eventTypeLabel';
                break;
            case "Place":
                $typeCategories = places;
                $typeLabel = 'placesLabel';
                break;
            case "Time":
                $typeCategories = [];   // todo: time stuff
                $typeLabel = 'timeLabel'; //?
                break;
        }
        ?>
        <script>
            var type = "<?php echo $type ?>";
            var typeID = "<?php echo $typeID ?>";
            var typeLabel = "<?php echo $typeLabel ?>";
        </script>
        <?php
        foreach (array_keys($typeCategories) as $category) { ?>
            <li>
                <a href="<?php echo BASE_URL;?>eventResults/?<?php echo $type;?>=<?php echo $category;?>">
                    <p class='type-title'><?php echo $category;?></p>
                    <div id="arrow"></div><span id="<?php echo $typeID . $typeCategories[$category];?>">0</span>
                </a>
            </li>
        <?php } ?>
    </ul>
</div>

<script src="<?php echo BASE_URL;?>assets/javascripts/explore.js"></script>

<script>
    $(document).ready(function () {
        console.log('counting by', type)
        $.ajax({
            url: BASE_URL + 'api/counterOfType',
            method: "GET",
            data: {type: type, category:"Events"},
            'success': function (data) {
                data = JSON.parse(data);

                console.log(data);

                data.forEach(function(records) {
                    console.log(records)
                    var category = records[typeLabel]['value'];
                    var count = records['count']['value'];
                    var span = $("a:contains("+category+")").find('span');

                    if ($(span).length > 0){
                        $(span).html(count)
                    }
                });
            }
        });
    });
</script>
