<!-- Author: Drew Schineller-->
<?php $cache_data = Json_GetData_ByTitle("About") ?>
<!-- About page-->
<!-- Heading image and title container-->
<div class="container header about-header">
    <div class="container middlewrap">
        <h1><?php echo $cache_data['title'] ?></h1>
    </div>
</div>
<!-- info container-->
<div class="container info">
    <div class="container infowrap">
        <?php echo $cache_data['descr'] ?></p>
    </div>
</div>

<!-- buttons -->
<div class="about-buttons">
    <div class="buttonwrap">
        <ul class="row">
            <li id="getinvolved">
                <a href="<?php echo BASE_URL?>getInvolved">
                    <div class="buttons">
                        <h3>Get Involved</h3>
                        <img class="about-button-chevron" id="green-chevron" src="<?php echo BASE_URL;?>assets/images/about-chevron-green.svg" alt="Chevron">
                        <img class="about-button-chevron" id="red-chevron" src="<?php echo BASE_URL;?>assets/images/about-chevron-red.svg" alt="Chevron">
                    </div>
                </a>
            </li>
            <li id="viewpartners">
                <a href="<?php echo BASE_URL?>ourPartners">
                    <div class="buttons">
                        <h3>Our Partners</h3>
                        <img class="about-button-chevron" id="green-chevron" src="<?php echo BASE_URL;?>assets/images/about-chevron-green.svg" alt="Chevron">
                        <img class="about-button-chevron" id="red-chevron" src="<?php echo BASE_URL;?>assets/images/about-chevron-red.svg" alt="Chevron">
                    </div>
                </a>
            </li>
            <li id="contactus">
                <a href="<?php echo BASE_URL?>contactUs">
                    <div class="buttons">
                        <h3>Contact Us</h3>
                        <img class="about-button-chevron" id="green-chevron" src="<?php echo BASE_URL;?>assets/images/about-chevron-green.svg" alt="Chevron">
                        <img class="about-button-chevron" id="red-chevron" src="<?php echo BASE_URL;?>assets/images/about-chevron-red.svg" alt="Chevron">
                    </div>
                </a>
            </li>
            <li id="ourteam">
                <a href="<?php echo BASE_URL?>ourTeam">
                    <div class="buttons">
                        <h3>Development Team</h3>
                        <img class="about-button-chevron" id="green-chevron" src="<?php echo BASE_URL;?>assets/images/about-chevron-green.svg" alt="Chevron">
                        <img class="about-button-chevron" id="red-chevron" src="<?php echo BASE_URL;?>assets/images/about-chevron-red.svg" alt="Chevron">
                    </div>
                </a>
            </li>
            <li id="references">
                <a href="<?php echo BASE_URL?>">
                    <div class="buttons">
                        <h3>See Our References</h3>
                        <img class="about-button-chevron" id="green-chevron" src="<?php echo BASE_URL;?>assets/images/about-chevron-green.svg" alt="Chevron">
                        <img class="about-button-chevron" id="red-chevron" src="<?php echo BASE_URL;?>assets/images/about-chevron-red.svg" alt="Chevron">
                    </div>
                </a>
            </li>
        </ul>
    </div>
</div>

<!-- text sections -->
<div class="container about-text">
    <p>In recent years, a growing number of archives, databases, and collections that organize and make sense of records of enslavement have become freely and readily accessible for scholarly and public consumption. This proliferation of projects and databases presents a number of challenges:  </p>
    <div class="container textwrap">
        <ul>
            <li>Disambiguating and merging individuals across multiple datasets is nearly impossible given their current, siloed nature;</li>
            <li>Searching, browsing, and quantitative analysis across projects is extremely difficult;</li>
            <li>It is often difficult to find projects and databases;</li>
            <li>There are no best practices for digital data creation;</li>
            <li>Many projects and datasets are in danger of going offline and disappearing.</li>
        </ul>
    </div>
    <p id="mid-paragraph">In response to these challenges, Matrix: The Center for Digital Humanities & Social Sciences at Michigan State University (MSU), in partnership with the MSU Department of History and scholars at multiple institutions, has developed Enslaved: Peoples of the Historic Slave Trade. Enslaved’s primary focus is people—individuals who were enslaved, owned slaves, or participated in slave trading. </p>
    <p>The project has identified the following five objectives:</p>
    <div class="container textwrap">
        <ol class="objectives">
            <li>People: Build an interconnected system of services and tools that would (1) Allow individuals involved in the slave trade to be identified and recognized across all participating project databases; (2) Allow those identified and recognized individuals to be searched, explored and visualized in the Enslaved Hub; (3) Connect those individuals to particular events and places with a Disambiguation Tool and Authoritative Name Service in Enslaved; and (4) Create at least 25 interactive biographies of people of the slave trade as exemplary models.</li>
            <li>Linked Open Data (LOD): To accomplish the focus on people, we are using Linked Open Data (LOD) to interconnect individual projects and databases.  A LOD-based approach facilitates federated searching and browsing across all linked project data on the Hub. It also creates a network and community framework that supports the preservation of current and future slave data projects.</li>
            <li>Best practices and workflow: For online database projects, which are proliferating at a rapid pace, scholars have not agreed on best practices. The Hub would be the space for disseminating best practices for data collection, metadata standards, ontologies, and workflows.  It would also provide guidance for participating in the Hub.</li>
            <li>Scholarly recognition: The project will institute an editorial board to review datasets and projects to be included in the Hub. Having an editorial board will ensure the quality of the data, and emphasize that the database or project has been published and is worthy of consideration for scholarly credit in review processes.</li>
            <li>Preservation and sustainability: The Hub will provide a space for preservation of datasets and help identify projects in danger of going offline. All facets will be open source and contribute to developing a wide community to support the sustainability of the project.</li>
        </ol>
    </div>
</div>
