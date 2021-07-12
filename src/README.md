# Enslaved Project Suite / Static Site Builder

### Installation instructions for development
1. Clone repository

        git clone git@direct.gitlab.matrix.msu.edu:matrix/enslaved-project-suite.git

2. Copy config and modify needed values:

        cp enslaved-project-suite/config.dist.php enslaved-project-suite/config.php

3. Install composer files:

        composer install

4. Install NPM files:

        npm install

5. To build site, run:

        npm run dev

Site will currently build at `enslaved-project-suite/build_local`
