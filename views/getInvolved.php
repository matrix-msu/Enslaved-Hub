<!-- Get Involved page-->
<?php $cache_data = Json_GetData_ByTitle("Get Involved"); ?>
<!-- Heading image and title container-->
<div class="container header about-header getinvolved-page">
    <div class="container middlewrap">
        <h4 class="last-page-header"><a id="last-page" href="<?php echo BASE_URL;?>about">
            <span id="previous-title">About / </span></a>
            <span id="current-title"><?php echo $cache_data['title'] ?></span>
        </h4>
        <h1><?php echo $cache_data['title'] ?></h1>
    </div>
</div>
<!-- info container-->
<div class="container info">
    <div class="container infowrap">
        <p><?php echo $cache_data['descr'] ?></p>
    </div>
</div>

<div class="container getinvolved-view">
  <a href="<?php echo BASE_URL?>">
      <div class="buttons">
          <h3>View Project & Scholar Directory</h3>
          <img class="about-button-chevron" id="green-chevron" src="<?php echo BASE_URL;?>assets/images/about-chevron-green.svg" alt="Chevron">
          <img class="about-button-chevron" id="red-chevron" src="<?php echo BASE_URL;?>assets/images/about-chevron-red.svg" alt="Chevron">
      </div>
  </a>
</div>

<!-- text sections -->
<div class="container getinvolved-submit">
    <h2>Submit Project or Scholar</h2>
    <p>Enslaved is always looking for new partners to participate in our project. If you have data about the historic slave trade, please email us at <span id="getinvolved-email">hello@enslaved.org</span>. We are willing to work with all projects, data types, and contributors. </p>
    <!-- <div class="buttonwrap">
        <ul class="row">
            <li> -->
                <a id="first-button" href="<?php echo BASE_URL?>">
                    <div class="buttons">
                        <h3>Project Submission</h3>
                        <img class="about-button-chevron" id="green-chevron" src="<?php echo BASE_URL;?>assets/images/about-chevron-green.svg" alt="Chevron">
                        <img class="about-button-chevron" id="red-chevron" src="<?php echo BASE_URL;?>assets/images/about-chevron-red.svg" alt="Chevron">
                    </div>
                </a>
            <!-- </li>
            <li> -->
                <a href="<?php echo BASE_URL?>">
                    <div class="buttons">
                        <h3>Scholar Submission</h3>
                        <img class="about-button-chevron" id="green-chevron" src="<?php echo BASE_URL;?>assets/images/about-chevron-green.svg" alt="Chevron">
                        <img class="about-button-chevron" id="red-chevron" src="<?php echo BASE_URL;?>assets/images/about-chevron-red.svg" alt="Chevron">
                    </div>
                </a>
            <!-- </li>
        </ul>
      </div> -->
</div>

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
