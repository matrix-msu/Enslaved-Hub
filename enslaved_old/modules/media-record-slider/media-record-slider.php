<main class="media-record-slider">
    <figure>
        <div class="fig-wrap">
            <button class="arrow-left"></button>
            <button class="arrow-right"></button>
            <img id="carousel-img" class="carousel-display modal" src="<?php echo BASE_IMAGE_URL;?>placeholder_MODAL.png" alt="carousel image">
        </div>
        <div id="carousel-controls" class="carousel-controls">
            <a href="<?php echo BASE_IMAGE_URL;?>placeholder_MODAL.png" target="_blank"><img class="external-link" src="<?php echo BASE_IMAGE_URL;?>external-link.svg"></a>
            <a href="<?php echo BASE_IMAGE_URL;?>placeholder_MODAL.png" download><img class="download" src="<?php echo BASE_IMAGE_URL;?>download.svg"></a>
            <img class="maximize" src="<?php echo BASE_IMAGE_URL;?>maximize.svg">
        </div>
    </figure>
    <div class="thumbnails"></div>
    <article>
        <section class="description">
            <p>Record Description. The image above wont just open up in a modal, but a fully functionaly image browsing experience. THe user will be able to zoom in and out and be able to pan essentially. The image will have a max height of 500px, and won’t be wider than the content area. Image will resize accordingly based on its proportions and the max height in the area above. User also has the options to open the image / media in a new tab, or download it directly.</p>
        </section>
        <section class="metadata">
            <div>
                <span>Metadata Title</span><p>Metadata Content</p>
            </div>
            <div>
                <span>Metadata Title</span><p>Metadata Content is longer</p>
            </div>
            <div>
                <span>Metadata Title</span><p>Metadata Content is longer</p>
            </div>
            <div>
                <span>Metadata Title is Longer</span><p>Metadata Content</p>
            </div>
            <div>
                <span>Title</span><p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. </p>
            </div>
            <div>
                <span>Title</span>
                <p>
                    <a href="#">Link Title</a>
                    <a href="#">Link Title</a>
                </p>
            </div>
        </section>
    </article>
</main>
<div class="modal-view">
    <div class="modal-wrap">
        <p class="close"><a id="modal-close"><img src="<?php echo BASE_IMAGE_URL;?>x.svg" alt="x.svg"></a></p>
        <button class="arrow-left"></button>
        <button class="arrow-right"></button>
        <div id="modal-img">
            <img id="modal-image" class="modal-img-view" src="<?php echo BASE_IMAGE_URL;?>placeholder_MODAL.png" alt="record modal image" draggable="false">
            <div>
                <span class="plus"><img src="<?php echo BASE_IMAGE_URL;?>plus.svg" alt="zoom in"></span>
                <span class="minus"><img src="<?php echo BASE_IMAGE_URL;?>minus.svg" alt="zoom out"></span>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?php echo BASE_MODULE_URL;?>media-record-slider/media-record-slider.js"></script>
<script type="text/javascript" src="<?php echo BASE_MODULE_URL;?>media-record-slider/media-record-slider-modal.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo BASE_MODULE_URL;?>media-record-slider/media-record-slider.css">
