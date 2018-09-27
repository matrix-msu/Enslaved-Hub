<!-- Page author: Drew Schineller-->
<?php
include 'header.php';
include 'functions/functions.php';
include 'functions/kora.php';
if (isset($_GET['kid']) && checkKID($_GET['kid'])) {
    $story = storyContent($_GET['kid']);
    // print_r($story);
    $images = [];
    if (isset($story['Images'])) {
        foreach ($story['Images']['value'] as $image) {
            $images[] = $image['url'];
        }
    }
}
else {

}
?>
<!-- Full Story page-->
<!-- Heading image and title container-->
<div class="container header fullstory">
    <div class="container middlewrap">
        <h4 class="last-page-header"><a id="last-page" href="<?php echo BASE_URL;?>stories"><span id="previous-title">Stories // </span></a><span id="current-title">Featured Story Title Goes Here Like This</span></h4>
        <?php
        if (isset($story['Title'])) {
            echo '<h1>'.$story['Title']['value'].'</h1>';
        }
        ?>
        <!-- <h1>Ibrahima Abd al-Rahman</h1>
        <h3>(18th/19th century)</h3> -->
    </div>
</div>
<!-- Main content (left/right columns)-->
<main class="story-content">
    <div class="container columnwrap">
        <article class="left-column">
            <section>
                <?php
                if (isset($story['Text'])) {
                    echo $story['Text']['value'];
                }
                ?>
                <!-- <p>Ibrahima Abd al-Rahman was one of only a few Africans enslaved and brought to America during the slave trade who was able to secure a return to Africa.  He was born c. 1762 in the Islamic kingdom of Futa Jallon, today located in Guinea. He was a son of the almaami of Futa Jallon, a Muslim theocratic ruler. Abd al-Rahman was raised in Timbo, the capital of Futa Jallon, where he studied the Koran as a young boy. He went on to study further in Jenne and Timbuktu, two major centers of learning, located in present day Mali.</p>
                <p>In 1788, while leading a trade delegation to the coast, Abd al-Rahman was ambushed and captured. He was then brought to the Gambia River, where he was sold to an Englishman, John Nevin. He was shackled and brought to the West Indies on the Africa along with 177 others, 164 of who disembarked in  in Dominica after 34 days of the Middle Passage. Around one-third of them, including Abd al-Rahman, were purchased by another ship owner, who sailed them to New Orleans before continuing on to Spanish Natchez (Mississippi). In Natchez, Abd al-Rahman alongside another slave, Samba, who had been with him since Futa Jallon, were sold to Thomas Foster for the combined price of $930, and continued on to Foster’s plantation outside of Natchez.</p>
                <p>Soon after arriving on Foster’s plantation, Abd al-Rahman ran away. Weeks later, after finding little help from the outside world, he returned. He became referred to by his master and others on the plantation as “Prince,” in reference to his claimed (and true) royal bloodline and regal bearing. Abd al-Rahman eventually came to oversee the plantation, and while he had been married in Futa Jallon, he remarried on Foster’s plantation, assuming he would never return home. Along with his wife, Isabella, he had five sons and four daughters.</p>
                <p>His royal lineage became accepted publicly after a chance meeting in 1807 with a doctor named John Coates Cox. Cox was the first European to have spent time in Abd al-Rahman’s hometown of Timbo, where he spent six months while recovering from an illness before Abd al-Rahman’s enslavement. Cox had even lived within Abd al-Rahman’s family compound. After recognizing him, Cox confirmed Abd al-Rahman’s heritage, which had been doubted by Foster and others on the plantation. He attempted to buy Abd al-Rahman’s freedom from Foster, who refused to sell him given his important role on the plantation.</p>
                <p>In 1826, Abd al-Rahman wrote a letter home in Arabic, but because of confusion (a local newspaperman who helped him assumed he was from Morocco), his letter was in fact sent to the US Consul in Morocco. After the Moroccans requested Abd al-Rahman’s freedom, U.S.Secretary of State Henry Clay recommended his release to none other than U.S.the American President, John Quincy Adams. In response to the Secretary of State’s request, Foster released Abd al-Rahman in 1828, on the condition that he leave the United States.</p>
                <p>Supported by the American Colonization Society and African American leaders in the North, Abd al-Rahman attempted to raise enough money to buy his family’s freedom, and was eventually able to free his wife and and some of their children. He sailed to Africa in February 1829 on a ship headed for Liberia, with the help of the U.S. government. He died a few months after returning to West Africa. Illness prevented him making the final journey of several hundred miles to his homeland of Futa Jallon. A year after his death, however, Isabella, would welcome eight children and grandchildren to Liberia, where the family would settle.</p> -->
            </section>
            <section class="online-resources">
                <?php
                if (isset($story['Online Resources'])) {
                    echo '<h3>Online Resources</h3>';
                    echo $story['Online Resources']['value'];
                }
                ?>
                <!-- <h3>Online Resources</h3>
                <p><a href="#">Thomas H. Gallaudet, A statement with regard to the Moorish prince, Abduhl Rahhahman (New York: Daniel Fanshaw, 1828)</a>, available online through the University of North Carolina, “Documenting the American South.”</p>
                <p><a href="#">Cyrus Griffin, “The African Homeland of Abdul Rahman Ibrahima,”</a> Southern Galaxy, Natchez, MI, May 29, June 5 & 12, July 5, 1828, available online at the National Humanities Center Resource Toolbox, “The Making of African American Identity: Vol. 1, 1500-1865.”</p>
                <p><a href="#">Information on the Africa, which brought Abd al-Rahman to America.</a> From SlaveVoyages.org.</p> -->
            </section>
            <section class="bibliography">
                <?php
                if (isset($story['Source'])) {
                    echo '<h3>Bibliography</h3>';
                    echo $story['Source']['value'];
                }
                ?>
                <!-- <h3>Bibliography</h3>
                <p>Terry Alford, Prince among Slaves (Oxford: Oxford University Press, 2007 [1977]).</p>
                <p>Allan D. Austin, African Muslims in Antebellum America: Transatlantic Stories and Spiritual Struggles (New York: Routledge, 1997 [1984)).</p>
                <p>Thomas H. Gallaudet, A statement with regard to the Moorish prince, Abduhl Rahhahman (New York: Daniel Fanshaw, 1828).</p>
                <p>Michael A. Gomez, “Muslims in Early America,” The Journal of Southern History, Vol. 60, No. 4 (1994), pp. 671-710.</p>
                <p>Cyrus Griffin, “The African Homeland of Abdul Rahman Ibrahima,” Southern Galaxy, Natchez, MI, May 29, June 5 & 12, July 5, 1828, available online at the National Humanities Center.</p> -->
            </section>
        </article>
        <article class="right-column">
            <div class="container sliderwrap">
                <div class="slider">
                </div>
                <div class="image-pagination">
                    <img id="prev-arrow" onclick="plusSlides(-1)" src="<?php echo BASE_URL?>assets/images/Arrow3.svg">
                    <div class="dotwrap" style="text-align:center">
                    </div>
                    <img id="next-arrow" onclick="plusSlides(1)" src="<?php echo BASE_URL?>assets/images/Arrow3.svg">
                </div>
                <div class="expand modal">
                    <img src="<?php echo BASE_URL?>assets/images/expand.svg">
                </div>
            </div>

            <div class="key-events">
                <?php
                if (isset($story['Timeline'])) {
                    echo '<h3 class="key-events-title">Key Events</h3>';
                    foreach ($story['Timeline']['value'] as $event) {
                        echo '<h3 class="key-events-date">'.$event['Date'].'</h3>';
                        echo '<p class="key-events-text">'.$event['Description'].'</p>';
                    }
                }
                ?>
                <!-- <h3 class="key-events-title">Key Events</h3>
                <h3 class="key-events-date">c. 1762</h3>
                <p class="key-events-text">Born in Timbo, in the kingdom of Futa Jallon (Guinea).</p>
                <h3 class="key-events-date">c. 1788</h3>
                <p class="key-events-text">Captured and sold to John Nevin, an English slave trader, captain of the slave ship Africa. Abd al-Rahman is transported to the West Indies and then, via New Orleans, to Spanish Natchez, where he is  sold to Thomas Foster, owner of  a small plantation outside of Natchez.</p>
                <h3 class="key-events-date">c. 1794 - December 25</h3>
                <p class="key-events-text">Marries Isabella, a Baptist slave on the plantation. Together, Abd al-Rahman and Isabella have five sons (Simon, Prince, Lee, Abram, and one unknown) as well as four daughters with unknown names.</p>
                <h3 class="key-events-date">c. 1807</h3>
                <p class="key-events-text">John Coates Cox arrives in Natchez. Cox knew Abd al-Rahman from his six-month stay in Timbo, Futa Jallon, where he had stayed as a guest of Abd al-Rahman’s family while recovering from an illness.</p>
                <h3 class="key-events-date">c. 1826</h3>
                <p class="key-events-text">Abd al-Rahman writes a letter home to Futa Jallon, which end sup in the hands of the Moroccan government. Given that he is a Muslim, the Moroccans request his freedom. As a result, Secretary of State Henry Clay advises President John Quincy Adams to ask Thomas Foster for his release.</p>
                <h3 class="key-events-date">c. 1828</h3>
                <p class="key-events-text">Abd al-Rahman is freed by Foster, and agrees to leave the United States. He also raises enough money to free his wife, Isabella. Over the course of 1828 and into early 1829, Abd al-Rahman campaigns for money to redeem his children and bring them back to West Africa. With the help of the American Colonization Society, he visits the White House and Congress. He also holds public events with African American leaders, including John Brown Russwurm, the editor of Freedom’s Journal, the first black newspaper, and David Walker, a Boston abolitionist who would soon publish the radical abolitionist pamphlet, Appeal to the Colored People of the World (1829).</p>
                <h3 class="key-events-date">c. 1829 - February 9</h3>
                <p class="key-events-text">Abd al-Rahman sails to Monrovia, Liberia, along with his wife Isabella and 153 other former slaves, returning to West Africa for the first time in forty years.</p>
                <h3 class="key-events-date">c. 1829 - Late</h3>
                <p class="key-events-text">Abd al-Rahman becomes sick and dies. The following year  his wife Isabella is reunited with some of her children and grandchildren in Liberia. She lived there until at least 1843, when her name appears in the Liberian census.</p>
                <h3 class="key-events-date">c. 1975</h3>
                <p class="key-events-text">Artemus Gaye, a seventh generation descendant of Abd al-Rahman, is born in Liberia. In the 1990s he flees Liberia during that nation’s civil war, finding refuge in America, where he has lectured on his ancestor’s legacy for our own times.</p> -->
            </div>
        </article>
    </div>
    <!-- Story Connections -->
    <div class="story-connections">
        <h2>Story Connections</h2>
        <div class="categories">
            <ul>
                <li class="unselected selected" id="people"><div class="person-image"></div>10 People</li>
                <li class="unselected" id="event"><div class="event-image"></div>3 Events</li>
                <li class="unselected" id="place"><div class="place-image"></div>3 Places</li>
                <li class="unselected" id="project"><div class="project-image"></div>2 Projects</li>
                <li class="unselected" id="source"><div class="source-image"></div>15 Sources</li>
            </ul>
        </div>
        <div class="connection-cards">
            <ul class="connect-row">
                <li>
                    <div class="cards">
                        <img src="<?php echo BASE_URL?>assets/images/Person-light.svg">
                        <h3>Firstname Lastname</h3>
                    </div>
                </li>
            </ul>
            <h4 class="load-more">Load More</h4>
        </div>
    </div>
    <!-- Related Stories-->
    <div class="container column related-card">
        <div class="container cardheader-wrap">
            <h2 class="column-header">Related Stories</h2>
        </div>
        <div class="container cardwrap">
            <ul class="row">
                <li>
                    <a href="<?php echo BASE_URL?>fullstory">
                        <div class="container cards">
                            <p class="card-title">Title of Featured Story Goes Here Like This</p>
                            <h4 class="card-view-story">View Story <div class="view-arrow"></div></h4>
                        </div>
                    </a>
                </li>
                <li>
                    <a href="<?php echo BASE_URL?>fullstory">
                        <div class="container cards">
                            <p class="card-title">Title of Featured Story Goes Here Like This</p>
                            <h4 class="card-view-story">View Story <div class="view-arrow"></div></h4>
                        </div>
                    </a>
                </li>
            </ul>
        </div>
    </div>

</main>
<!-- Modal View -->
<div class="modal-view">
    <div class="modal-image">
    </div>
</div>

<script>
var result_array = <?php echo json_encode($images); ?>
</script>
<script src="<?php echo BASE_URL;?>assets/javascripts/slider.js"></script>
<script src="<?php echo BASE_URL;?>assets/javascripts/modal.js"></script>
<script src="<?php echo BASE_URL;?>assets/javascripts/connections.js"></script>

<?php include 'footer.php';?>