<?php // Check if form was submitted:
    require_once ( __DIR__ . '/config.php' ) ;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['recaptcha_response'])) {

    // Build POST request:
    $recaptcha_url = 'https://www.google.com/recaptcha/api/siteverify';
    $recaptcha_secret = RECAPTCHA_SECRET_KEY;
    $recaptcha_response = $_POST['recaptcha_response'];

    // Make and decode POST request:
    $recaptcha = file_get_contents($recaptcha_url . '?secret=' . $recaptcha_secret . '&response=' . $recaptcha_response);
    $recaptcha = json_decode($recaptcha);

    // Take action based on the score returned:
    if ($recaptcha->score >= 0.5) {
        // Verified - send email
	$body="";
	$opts = array('http' =>
	  array(
	    'method'  => 'POST',
	    'header'  => "PRIVATE-TOKEN: ".GITLAB_API_KEY."\r\n",
	    'content' => $body,
	    'timeout' => 60
	  )
	);

	// print var_dump($_POST);

	$description = $_POST{name} . " reports on the page " . $_POST{url} . " :\n\n" . $_POST{description} ;

	$context  = stream_context_create($opts);
	$url = 'https://gitlab.matrix.msu.edu/api/v4/projects/12/issues'.
		'?title='.urlencode($_POST{title}).
		'&labels=BetaSite'.
		'&assignee_ids=6'.
		'&description='.urlencode($description)
	;
	$result = file_get_contents($url, false, $context, 0, 40000);

	print "Your issue has been reported.";

    } else {
	print "Recaptcha failed";
        // Not verified - show form error
    }

} ?>

<?php

?>
