<!-- Author: Drew Schineller-->
<?php $cache_data = Json_GetData_ByTitle("About") ?>
<!-- About page-->
<!-- Heading image and title container-->
<div class="container header">
    <img class="header-background full-height" src="<?php echo BASE_URL;?>assets/images/enslaved-header-bg.jpg" alt="Header Background Image">
    <div class="container middlewrap">
        <h1><?php echo $cache_data['title'] ?></h1>
    </div>
</div>
<!-- info container-->
<div class="container info">
    <div class="container infowrap">
        <p><?php echo $cache_data['descr'] ?></p>
    </div>
</div>
<!-- about cards -->
<div class="about-cards">
    <div class="cardwrap">
        <ul class="row">
            <li id="getinvolved">
                <a href="<?php echo BASE_URL?>getInvolved">
                    <div class="cards">
                        <h2>Learn How to Get Involved</h2>
                    </div>
                </a>
            </li>
            <li id="viewpartners">
                <a href="<?php echo BASE_URL?>ourPartners">
                    <div class="cards">
                        <h2>View our Partners</h2>
                    </div>
                </a>
            </li>
            <li id="contactus">
                <a href="<?php echo BASE_URL?>contactUs">
                    <div class="cards">
                        <h2>Contact Us</h2>
                    </div>
                </a>
            </li>
            <li id="contactus">
                <a href="<?php echo BASE_URL?>ourTeam">
                    <div class="cards">
                        <h2>View our Team</h2>
                    </div>
                </a>
            </li>
            <li id="viewpartners">
                <a href="<?php echo BASE_URL?>references">
                    <div class="cards">
                        <h2>See our References</h2>
                    </div>
                </a>
            </li>
            <li id="getinvolved">
                <a href="#">
                    <div class="cards">
                        <h2>Back To Top</h2>
                    </div>
                </a>
            </li>
        </ul>
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