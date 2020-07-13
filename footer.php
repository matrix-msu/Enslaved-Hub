<footer id="footer">

<!-- New Gitlab ticket bits -->
<center>
<button type="button" class="issuecollapsible">Report issue</button>
<div class="issuereport" style="display:none">
<form id="newGitlabTicket">
	<input name="name"  size=80 placeholder="Your name" type="text" value="" /> <br /> 
	<input name="title" size=80 placeholder="New Ticket Title"  /><br /> 
	<textarea name="description" cols="80" placeholder="Detailed description"  rows="5"></textarea><br /> 
	<input id="url" name="url" type=hidden />
	<input type="hidden" name="recaptcha_response" id="recaptchaResponse">
	<input type="submit" value="Open Ticket">
</form>
This site is protected by reCAPTCHA and the Google <a href=”https://policies.google.com/privacy”>Privacy Policy</a> and <a href=”https://policies.google.com/terms”>Terms of Service</a> apply.
</div>
</center>
<script>
var coll = document.getElementsByClassName("issuecollapsible");
var i;

for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var content = this.nextElementSibling;
    if (content.style.display === "block") {
      content.style.display = "none";
    } else {
      content.style.display = "block";
    }
  });
}
</script>
<script>
window.addEventListener( "load", function () {
  function sendData() {
    const XHR = new XMLHttpRequest();

    form.getElementsByTagName("input").namedItem("url").value = window.location.href;

    // Bind the FormData object and the form element
    const FD = new FormData( form );

    // Define what happens on successful data submission
    XHR.addEventListener( "load", function(event) {
      alert( event.target.responseText );
    } );

    // Define what happens in case of error
    XHR.addEventListener( "error", function( event ) {
      alert( 'Oops! Something went wrong.' );
    } );

    // Set up our request
    XHR.open( "POST", "<?php echo BASE_URL;?>newticket.php" );

    // The data sent is what the user provided in the form
    XHR.send( FD );
  }
 
  // Access the form element...
  const form = document.getElementById( "newGitlabTicket" );

  // ...and take over its submit event.
  form.addEventListener( "submit", function ( event ) {
    event.preventDefault();
    grecaptcha.execute("<?php echo RECAPTCHA_SITE_KEY;?>", { action: 'newTicket' }).then(function (token) {
        var recaptchaResponse = document.getElementById('recaptchaResponse');
        recaptchaResponse.value = token;
        sendData();
            });
    
  } );
} );
</script>
<!-- End New Gitlab ticket bits -->
    <div class="footerwrap">
        <a href="https://mellon.org" target="_blank"><img src="<?php echo BASE_IMAGE_URL;?>Footer-mellon.svg"  alt="The Andew W. Mellon Foundation Logo" /></a>
      <a href="https://www.matrix.msu.edu" target="_blank"><img src="<?php echo BASE_IMAGE_URL;?>Footer-Matrix-Logo-White.svg"  alt="Matrix: The Center for Digital Humanities and Social Sciences Logo"/></a>
      <a href="https://msu.edu" target="_blank"><img src="<?php echo BASE_IMAGE_URL;?>Footer-MSUWordmark.svg"  alt="Michigan State University Logo"/></a>
      <a href="https://umd.edu" target="_blank"><img src="<?php echo BASE_IMAGE_URL;?>Footer-UMD.svg" alt="University of Maryland Logo" /></a>
        
        
    </div>
</footer>

<!--JS to jump the page back up to top with the Back to Top Button
  <button class="top-button">Back to Top</button>
-->
<script src="<?php echo BASE_URL;?>assets/javascripts/footer.js"></script>
