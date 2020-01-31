<?php $cache_data = Json_GetData_ByTitle("scholarSubmission");
$bg = ['enslaved-header-bg.jpg','enslaved-header-bg2.jpg',
        'enslaved-header-bg3.jpg','enslaved-header-bg4.jpg',
        'enslaved-header-bg5.jpg','enslaved-header-bg6.jpg',
        'enslaved-header-bg7.jpg'];
$randIndex = array_rand($bg);


?>


<div class="container header">
    <div class="image-container search-page image-only">
	    <div class="container middlewrap">
    <h4 class="last-page-header"><a id="last-page" href="<?php echo BASE_URL;?>about"><span id="previous-title">About / </span></a><a id="last-page" href="<?php echo BASE_URL;?>getInvolved"><span id="previous-title">Get Involved / </span></a><span id="current-title">Scholar Submission</span></h4>
        <div class="search-title">
            <h1>Scholar Submission</h1>
        </div>
    </div>
      <div class="image-background-overlay"></div>
      <img class="header-background scholarSubmission-page" src="<?php echo BASE_URL;?>assets/images/<?php echo $bg[$randIndex];?>" alt="Enslaved Background Image">

    </div>
</div>
<main class="direct-search">
    <div class="searchwrap">
        <form action="<?php echo BASE_URL;?>" method="get" onsubmit="handleSubmit()" autocomplete="off">
          <!--   <?php echo $cache_data['descr'] ?> -->
          <p>If you would like to be listed within our Directory, please submit your information into the form below.</p>
            <div class="search-section submission-form">
                <div class="inputwrap">
                    <label for="name">Name</label>
                    <input class="input-field" id="name" name="name" type="text" placeholder="Enter Name"/>
                </div>
                <div class="inputwrap">
                    <label for="affiliation">Affiliation</label>
                    <input class="input-field" id="affiliation" name="affiliation" type="text" placeholder="Enter Affiliation"/>
                </div>
                <div class="inputwrap">
                    <label for="email">E-mail</label>
                    <input class="input-field" id="email" name="email" type="text" placeholder="Enter E-mail"/>
                </div>
                <div class="inputwrap">
                    <label for="personal-website">Personal Website</label>
                    <input class="input-field" id="personal-website" name="personal-website" type="text" placeholder="Enter Personal Website"/>
                </div>
                <div class="inputwrap">
                    <label for="research-specialization">Research and Specialization</label>
                    <input class="input-field" id="research-specialization" name="research-specialization" type="text" placeholder="Enter Research and Specialization"/>
                </div>
            </div>


            <div class="buttonwrap">
                <button id="direct-submit" type="submit" data-submit="...Sending">Submit Scholar</button>
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
