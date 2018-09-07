<!-- Page author: Drew Schineller-->
<?php include 'header.php';?>
<!-- Heading image and title container-->
<div class="container header stories">
    <div class="container middlewrap">
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
        <li>
            <a href="<?php echo BASE_URL?>peopleResults">Male<div id="arrow"></div><span>6,000</span></a>
        </li>
        <li>
            Female<div id="arrow"></div><span>6,000</span>
        </li>
        <li>
            Unidentified<div id="arrow"></div><span>6,000</span>
        </li>
    </ul>
</div>

<script src="<?php echo BASE_URL;?>assets/javascripts/explore.js"></script>

<?php include 'footer.php';?>