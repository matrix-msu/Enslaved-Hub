<!-- Author: Drew Schineller-->
<!-- Contact Us page-->
<?php $cache_data = Json_GetData_ByTitle("Contact Us") ?>
<!-- Heading image and title container-->
<div class="container header">
     <div class="container middlewrap">
        <h4 class="last-page-header"><a id="last-page" href="<?php echo BASE_URL;?>about">
            <span id="previous-title">About // </span></a>
            <span id="current-title"><?php echo $cache_data['title'] ?></span>
        </h4>
        <h1><?php echo $cache_data['title'] ?></h1>
    </div>
</div>
<!-- info container-->
<div class="container info">
    <div class="container infowrap">
        <p><?php echo $cache_data["descr"] ?></p>
    </div>
</div>
<!-- contact form -->
<!-- 
<div class="contact-form">
    <div class="formwrap">
        <form>
            <div class="input-section">
                <div class="inputwrap">
                    <label for="name">Your Name</label>
                    <input class="input-field" id="name" name="name" type="text" placeholder="Enter Your Name"/>
                </div>
                <div class="inputwrap">
                    <label for="email">Your Email</label>
                    <input class="input-field" id="email" name="email" type="text" placeholder="Enter Your Email"/>
                </div>
                <div class="inputwrap" id="message">
                    <label for="message">Your Message</label>
                    <textarea class="input-field" id="message" name="message" type="text" placeholder="Enter Your Message"></textarea>
                </div>
            </div>
            <div class="buttonwrap">
                <button id="direct-submit" name="submit" type="submit" data-submit="...Sending">Send Message</button>
            </div>
        </form>
    </div>
</div>
 -->