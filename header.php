<!-- Header/Navigation -->
<header class="nav-header">
    <div class="headerwrap">
        <div class="leftnav">
            <div class="logo"><a href="<?php echo BASE_URL;?>"><img src="<?php echo BASE_IMAGE_URL;?>Logo.svg" alt="Enslaved Peoples of Historical Slave Trade"/></a></div>
        </div>
        <div class="dropdown-menu">
            <div class="responsive-menu">
                <a><a class="nav-link" id="menu-button">Menu</a><span class="dropdown-button"><img class="hamburger" src="<?php echo BASE_URL;?>assets/images/hamburger.svg" alt="hambrger.svg"/></span></a>
            </div>
        </div>
        <div class="rightnav">
            <ul class="nav-menu">
            <?php
            $navigations = Json_GetNavigationData();
            foreach ($navigations as $nav)
            {$toUrl = ($nav[0] == "Explore") ? BASE_URL."explore/" : BASE_URL;
                if(count($nav[1]) > 0 && $nav[1][0] != null) {
                  $link = ($nav[0] == "Explore") ? BASE_URL."explore/people" : BASE_URL.strtolower($nav[0]);?>
                <li class="nav-item drop-link">
                    <a class="nav-link unselected" id="<?php echo strtolower($nav[0])?>" href="<?php echo $link?>"><?php echo $nav[0]?></a>
                    <span class="drop-carat"><img src="<?php echo BASE_IMAGE_URL;?>Arrow.svg" alt="dropdown carrat"/></span>
                    <ul class="sub-list">
                        <li class="subwrap" id="explore-sub">
                            <?php foreach ($nav[1] as $sub_nav) { ?>
                                <a class="nav-sublink" href="<?php echo $toUrl.lcfirst(str_replace(' ', "", $sub_nav))?>"><?php echo $sub_nav ?></a>
                            <?php } ?>
                        </li>
                    </ul>
                </li>
                <?php continue;} ?>
                <li class="nav-item">
                    <a class="nav-link unselected" id="<?php echo strtolower($nav[0])?>" href="<?php echo BASE_URL.lcfirst($nav[0])?>"><?php echo $nav[0]?></a>
                    <?php echo ($nav[0] == "Search") ? '<img class="search-icon" src="'.BASE_IMAGE_URL.'search.svg" alt="search icon" />' : "" ?>
                </li>
            <?php } ?>

            		<li class="nav-item donate">
                    <a class="nav-link donate unselected" href="<?php echo BASE_URL;?>support-our-mission">Support Our Mission</a>
                </li>

            </ul>
        </div>
    </div>
</header>
