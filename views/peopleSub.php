<!-- Page author: Drew Schineller-->
<!-- Heading image and title container-->
<div class="container header stories">
    <div class="container middlewrap">
        <h4 class="last-page-header"><a id="last-page" href="<?php echo BASE_URL;?>explorePeople"><span id="previous-title">People // </span></a><span id="current-title">Gender</span></h4>
        <h1>Gender</h1>
    </div>
</div>
<!-- explore by -->
<div class="explore-by">
    <div class="sort-cards">
        <p>Sort Genders By <img class="sort-arrow" src="<?php echo BASE_URL?>assets/images/Arrow2.svg" alt="sort cards button"/></p>
        <ul id="submenu" class="pagenum-menu">
            <li>Alphabetical (A-Z)</li>
            <li>Alphabetical (Z-A)</li>
            <li>Resources (Most to Least)</li>
            <li>Resources (Least to Most)</li>
        </ul>
    </div>
    <ul class="cards">
      <?php foreach (sexTypes as $sex => $qvalue) {
        echo '<li>
            <a href="'.BASE_URL.'peopleResults/?sex='.$sex.'"><p class="type-title">'.$sex.'</p><div id="arrow"></div><span id="gender-'.$qvalue.'"></span></a></li>';
      }?>
    </ul>
</div>

<script src="<?php echo BASE_URL;?>assets/javascripts/explore.js"></script>

<script>
    $(document).ready(function () {

        $.each(<?php echo json_encode(sexTypes);?>, function(){
            var temp = this;
            $.ajax({
                url: BASE_URL + 'api/counterOfGender',
                method: "GET",
                data: {gender: temp},
                'success': function (data) {
                    $('#gender-'+temp).html(data);
                }
            });
        });

    });
</script>