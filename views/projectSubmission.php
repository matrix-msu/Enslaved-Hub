<div class="container header">
    <div class="image-container search-page image-only">
	    <div class="container middlewrap">
    <h4 class="last-page-header"><a id="last-page" href="<?php echo BASE_URL;?>about"><span id="previous-title">About / </span></a><a id="last-page" href="<?php echo BASE_URL;?>getInvolved"><span id="previous-title">Get Involved / </span></a><span id="current-title">Project Submission</span></h4>
        <div class="search-title">
            <h1>Project Submission</h1>
        </div>
    </div>
      <div class="image-background-overlay"></div>
      <img class="header-background full-height search-page" src="<?php echo BASE_URL;?>assets/images/enslaved-header-bg2.jpg" alt="Enslaved Background Image">
    
    </div>
</div>
<main class="direct-search">
    <div class="searchwrap">
        <form action="<?php echo BASE_URL;?>" method="get" onsubmit="handleSubmit()" autocomplete="off">
          <!--   <?php echo $cache_data['descr'] ?> -->
          <p>If you would like your project to be listed within our Directory, please submit your project’s information into the form below. </p>
            <div class="search-section submission-form">
                <div class="inputwrap">
                    <label for="name">Name</label>
                    <input class="input-field" id="name" name="name" type="text" placeholder="Enter Name"/>
                </div>
                <div class="inputwrap">
                    <label for="developer">Developer(s)</label>
                    <input class="input-field" id="developer" name="developer" type="text" placeholder="Enter Developer(s)"/>
                </div>
                <div class="inputwrap">
                    <label for="affiliation">Affiliation</label>
                    <input class="input-field" id="affiliation" name="affiliation" type="text" placeholder="Enter Affiliation"/>
                </div>
                <div class="inputwrap">
                    <label for="location">Location</label>
                    <input class="input-field" id="location" name="location" type="text" placeholder="Enter Location"/>
                </div>
                <div class="inputwrap">
                    <label for="url">URL</label>
                    <input class="input-field" id="url" name="url" type="text" placeholder="Enter URL"/>
                </div>
                <div class="inputwrap">
                    <label for="notes">Notes</label>
                    <input class="input-field" id="notes" name="notes" type="text" placeholder="Enter Notes"/>
                </div>
            </div>
            

            <div class="buttonwrap">
                <button id="direct-submit" type="submit" data-submit="...Sending">Submit Project</button>
            </div>
        </form>
    </div>
</main>
<script src="<?php echo BASE_URL;?>assets/javascripts/search.js"></script>
<script>
    autocomplete(document.getElementById("place"), [<?php echo '"'.implode('","', qPlaces).'"' ?>]);
    autocomplete(document.getElementById("ethno"), [<?php echo '"'.implode('","', qethnodescriptor).'"' ?>]);
    autocomplete(document.getElementById("age"), [<?php echo '"'.implode('","', qages).'"' ?>]);
</script>