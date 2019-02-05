
<link rel="stylesheet" type="text/css" href="<?php echo BASE_MODULE_URL;?>modal/modal.css">


<figure class="image-modal">
	<div class="fig-wrap">
		<img id="carousel-img" class="carousel-display modal" src="<?php echo BASE_IMAGE_URL;?>placeholder_MODAL.png" alt="carousel image">
	</div>
	<div id="carousel-controls" class="carousel-controls">
		<img class="maximize" src="<?php echo BASE_IMAGE_URL;?>maximize.svg">
	</div>
</figure>

<div id="modal-dim-background-img"></div>
<div class="modal-view-image">
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

<script type="text/javascript" src="<?php echo BASE_MODULE_URL;?>modal/modal.js"></script>
