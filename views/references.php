<!-- Get Involved page-->
<?php $cache_data = Json_GetData_ByTitle("References");


$bg = ['enslaved-header-bg.jpg','enslaved-header-bg2.jpg',
        'enslaved-header-bg3.jpg','enslaved-header-bg4.jpg',
        'enslaved-header-bg5.jpg','enslaved-header-bg6.jpg',
        'enslaved-header-bg7.jpg'];
$randIndex = array_rand($bg);

?>
<!-- Heading image and title container-->
<!-- <div class="container header">
    <div class="container middlewrap">
        <h1><?php echo $cache_data['title'] ?></h1>
        <img class="header-background home-page" src="<?php echo BASE_URL;?>assets/images/<?php echo $bg[$randIndex];?>" alt="Enslaved Background Image">
    </div>
</div> -->

<div class="container header references-header references-page">
    <img class="header-background references-page" src="<?php echo BASE_URL;?>assets/images/<?php echo $bg[$randIndex];?>" alt="Enslaved Background Image">
    <div class="container middlewrap">
        <h1><?php echo $cache_data['title'] ?></h1>
    </div>
</div>
<!-- info container-->
<div class="container info">
    <div class="container infowrap">
        <div class="banner">
          <div class="banner-title">Banner Imagery</div>
          <div class="banner-content">
            <p class="title">A Cotton Plantation on the Mississippi</p>
            <p class="reference">Yale University Art Gallery</p>
            <a href="https://artgallery.yale.edu/collections/objects/142321" target="_blank">https://artgallery.yale.edu/collections/objects/142321</a>
          </div>
          <div class="banner-content">
            <p class="title">Peddlers or Hawkers, Rio de Janeiro, Brazil, 1819-1820</p>
            <p class="reference">Slavery Images</p>
            <a href="http://www.slaveryimages.org/s/slaveryimages/item/727" target="_blank">http://www.slaveryimages.org/s/slaveryimages/item/727</a>
          </div>
          <div class="banner-content">
            <p class="title">Market Stall and Market Women, Rio de Janeiro, Brazil, 1819-1820</p>
            <p class="reference">Slavery Images</p>
            <a href="http://www.slaveryimages.org/s/slaveryimages/item/725" target="_blank">http://www.slaveryimages.org/s/slaveryimages/item/725</a>
          </div>
          <div class="banner-content">
            <p class="title">Charles Landseer - View of Sugarloaf Mountain from the Silvestre Road</p>
            <p class="reference">Wikimedia Commons</p>
            <a href="https://commons.wikimedia.org/wiki/File:Charles_Landseer_-_View_of_Sugarloaf_Mountain_from_the_Silvestre_Road_-_Google_Art_Project.jpg" target="_blank">https://commons.wikimedia.org/wiki/File:Charles_Landseer_-_View_of_Sugarloaf_Mountain_from_the_Silvestre_Road_-_Google_Art_Project.jpg</a>
          </div>
          <div class="banner-content">
            <p class="title">Sugar Cane Harvest, Antigua, West Indies, 1823</p>
            <p class="reference">Slavery Images</p>
            <a href="http://www.slaveryimages.org/s/slaveryimages/item/1113" target="_blank">http://www.slaveryimages.org/s/slaveryimages/item/1113</a>
          </div>
          <div class="banner-content">
            <p class="title">Digging Holes for Planting Sugar Cane, Antigua, West Indies, 1823</p>
            <p class="reference">Slavery Images</p>
            <a href="http://www.slaveryimages.org/s/slaveryimages/item/1116" target="_blank">http://www.slaveryimages.org/s/slaveryimages/item/1116</a>
          </div>
          <div class="banner-content">
            <p class="title">Plantation Settlement, Surinam, ca. 1860</p>
            <p class="reference">Slavery Images</p>
            <a href="http://www.slaveryimages.org/s/slaveryimages/item/1396" target="_blank">http://www.slaveryimages.org/s/slaveryimages/item/1396</a>
          </div>
        </div>
        <div class="bibliographies">
          <div class="biblio-title">Bibliographies</div>
          <div class="biblio-content">
            <p class="citation">
              Egerton, Douglas. He Shall Go Free: The Lives of Denmark Vesey. New York: Rowman and Littlefield, 2004.<br><br>
              Millett, Nathaniel. "Pritchard, “Gullah” Jack." African American National Biography, edited by Ed. Henry Louis Gates Jr.. , edited by and Evelyn Brooks Higginbotham. . Oxford African American Studies Center, http://www.oxfordaasc.com/article/opr/t0001/e4741 (accessed Thu Sep 05 11:11:29 EDT 2019).<br><br>
              Robertson, David. Denmark Vesey. New York: Vintage Books, 2000.<br><br>
              Silverman, Susan, and Lois Walker. A Documented History of Gullah Jack Pritchard and the Denmark Vesey Slave Insurrection of 1822. Lewiston, NY: Edwin Mellen Press, 2001.<br><br>
              Egerton, Douglas. He Shall Go Free: The Lives of Denmark Vesey. New York: Rowman and Littlefield, 2004.<br><br>
              Millett, Nathaniel. "Pritchard, “Gullah” Jack." African American National Biography, edited by Ed. Henry Louis Gates Jr.. , edited by and Evelyn Brooks Higginbotham. . Oxford African American Studies Center, http://www.oxfordaasc.com/article/opr/t0001/e4741 (accessed Thu Sep 05 11:11:29 EDT 2019).<br><br>
              Robertson, David. Denmark Vesey. New York: Vintage Books, 2000.<br><br>
              Silverman, Susan, and Lois Walker. A Documented History of Gullah Jack Pritchard and the Denmark Vesey Slave Insurrection of 1822. Lewiston, NY: Edwin Mellen Press, 2001.<br><br>
              Egerton, Douglas. He Shall Go Free: The Lives of Denmark Vesey. New York: Rowman and Littlefield, 2004.<br><br>
              Millett, Nathaniel. "Pritchard, “Gullah” Jack." African American National Biography, edited by Ed. Henry Louis Gates Jr.. , edited by and Evelyn Brooks Higginbotham. . Oxford African American Studies Center, http://www.oxfordaasc.com/article/opr/t0001/e4741 (accessed Thu Sep 05 11:11:29 EDT 2019).<br><br>
              Robertson, David. Denmark Vesey. New York: Vintage Books, 2000.<br><br>
              Silverman, Susan, and Lois Walker. A Documented History of Gullah Jack Pritchard and the Denmark Vesey Slave Insurrection of 1822. Lewiston, NY: Edwin Mellen Press, 2001.<br><br>
              Egerton, Douglas. He Shall Go Free: The Lives of Denmark Vesey. New York: Rowman and Littlefield, 2004.<br><br>
              Millett, Nathaniel. "Pritchard, “Gullah” Jack." African American National Biography, edited by Ed. Henry Louis Gates Jr.. , edited by and Evelyn Brooks Higginbotham. . Oxford African American Studies Center, http://www.oxfordaasc.com/article/opr/t0001/e4741 (accessed Thu Sep 05 11:11:29 EDT 2019).<br><br>
              Robertson, David. Denmark Vesey. New York: Vintage Books, 2000.<br><br>
              Silverman, Susan, and Lois Walker. A Documented History of Gullah Jack Pritchard and the Denmark Vesey Slave Insurrection of 1822. Lewiston, NY: Edwin Mellen Press, 2001.<br><br>
              Egerton, Douglas. He Shall Go Free: The Lives of Denmark Vesey. New York: Rowman and Littlefield, 2004.<br><br>
              Millett, Nathaniel. "Pritchard, “Gullah” Jack." African American National Biography, edited by Ed. Henry Louis Gates Jr.. , edited by and Evelyn Brooks Higginbotham. . Oxford African American Studies Center, http://www.oxfordaasc.com/article/opr/t0001/e4741 (accessed Thu Sep 05 11:11:29 EDT 2019).<br><br>
              Robertson, David. Denmark Vesey. New York: Vintage Books, 2000.<br><br>
              Silverman, Susan, and Lois Walker. A Documented History of Gullah Jack Pritchard and the Denmark Vesey Slave Insurrection of 1822. Lewiston, NY: Edwin Mellen Press, 2001.
            </p>
          </div>
        </div>

        <!-- <p><?php echo $cache_data["descr"] ?></p> -->

    </div>
</div>


<!-- text sections -->
<!--
<div class="container about-text">
    <div class="container textwrap">
        <h2>Subheader Tincidunt praesent semper feugiat nibh sed pulvinar. </h2>
        <p>Brief Info on Project. Tincidunt praesent semper feugiat nibh sed pulvinar. Elementum nisi quis eleifend quam adipiscing vitae proin. Suscipit tellus mauris a diam maecenas sed enim. Neque egestas congue quisque egestas diam in arcu cursus euismod. Bibendum at varius vel pharetra. Vulputate sapien nec sagittis aliquam malesuada bibendum arcu vitae. Praesent elementum facilisis leo vel fringilla est ullamcorper eget. Tellus at urna condimentum mattis pellentesque. Tincidunt augue interdum velit euismod in pellentesque massa placerat. Eu tincidunt tortor aliquam nulla facilisi cras fermentum odio. </p>
        <br>
        <p>Et malesuada fames ac turpis. Orci eu lobortis elementum nibh. Nibh venenatis cras sed felis. Mattis ullamcorper velit sed ullamcorper morbi tincidunt. Egestas sed sed risus pretium quam vulputate dignissim suspendisse in. Viverra accumsan in nisl nisi scelerisque eu ultrices vitae auctor. Faucibus et molestie ac feugiat sed lectus. Vitae tortor condimentum lacinia quis vel eros donec ac odio. Tincidunt eget nullam non nisi est sit amet. Pharetra convallis posuere morbi leo urna molestie at elementum. Quis varius quam quisque id diam vel. Pulvinar sapien et ligula ullamcorper. Aliquam ultrices sagittis orci a scelerisque purus semper eget. Pellentesque pulvinar pellentesque habitant morbi tristique. Eu lobortis elementum nibh tellus molestie nunc non. </p>
    </div>
    <div class="container textwrap">
        <h2>Subheader Tincidunt praesent semper feugiat nibh sed pulvinar. </h2>
        <p>Brief Info on Project. Tincidunt praesent semper feugiat nibh sed pulvinar. Elementum nisi quis eleifend quam adipiscing vitae proin. Suscipit tellus mauris a diam maecenas sed enim. Neque egestas congue quisque egestas diam in arcu cursus euismod. Bibendum at varius vel pharetra. Vulputate sapien nec sagittis aliquam malesuada bibendum arcu vitae. Praesent elementum facilisis leo vel fringilla est ullamcorper eget. Tellus at urna condimentum mattis pellentesque. Tincidunt augue interdum velit euismod in pellentesque massa placerat. Eu tincidunt tortor aliquam nulla facilisi cras fermentum odio. </p>
        <br>
        <p>Et malesuada fames ac turpis. Orci eu lobortis elementum nibh. Nibh venenatis cras sed felis. Mattis ullamcorper velit sed ullamcorper morbi tincidunt. Egestas sed sed risus pretium quam vulputate dignissim suspendisse in. Viverra accumsan in nisl nisi scelerisque eu ultrices vitae auctor. Faucibus et molestie ac feugiat sed lectus. Vitae tortor condimentum lacinia quis vel eros donec ac odio. Tincidunt eget nullam non nisi est sit amet. Pharetra convallis posuere morbi leo urna molestie at elementum. Quis varius quam quisque id diam vel. Pulvinar sapien et ligula ullamcorper. Aliquam ultrices sagittis orci a scelerisque purus semper eget. Pellentesque pulvinar pellentesque habitant morbi tristique. Eu lobortis elementum nibh tellus molestie nunc non. </p>
    </div>
</div>
 -->
