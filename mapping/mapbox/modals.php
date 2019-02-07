<div id='comingSoon' class='modal'>
	<div>
		<div class='modal-x-btn'>
			<img src='assets/svgs/Close.svg' class='closeComingSoon' />
		</div>
		<div class='modal-content'>
			<h1>Feature coming soon!</h1>
			<p>This feature is currently under development and will be available soon!</p>
			<form>
				<button type='button' class='red-button closeComingSoon'>Sounds good!</button>
			</form>
		</div>
	</div>
</div>

<?php
if ($pagename == 'Home') {
	echo
	"<div id='underDev' class='modal'>
		<div>
			<div class='modal-x-btn'>
				<img src='assets/svgs/Close.svg' class='closeUnderDev' />
			</div>
			<div class='modal-content'>
				<h1>The New AODL is Currently in Preview</h1>
				<p>The new version of the African Online Digital Library is currently under active development. Until the final version is completed and launched, enjoy this preview. All legacy projects and collections are accessible, but cross project searching and browsing is not yet available.</p>
				<form>
					<div class='modalCheckbox'>
						<input type='checkbox' value='1' id='modalCheckboxInput' name='' />
						<label for='modalCheckboxInput' class='modalCheckboxBox'></label>
						<label for='modalCheckboxInput' class='modalCheckboxText'>Don't show again</label>
					</div>
					<button type='button' class='red-button closeUnderDev'>Sounds good!</button>
				</form>
			</div>
		</div>
	</div>";
}
?>
