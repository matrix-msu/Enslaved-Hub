# The curent working branch for development is the automation branch.<br />Make sure you've switched over using the install instructions below.

## Enslaved Hub / Static Site Builder

### Installation instructions for development
1. Copy script to terminal:

        git clone git@direct.gitlab.matrix.msu.edu:matrix/enslaved.git
        cd enslaved
        git checkout automation
        cp src/config.dist.php src/config.php
        cp src/source/config.dist.php src/source/config.php
        cp src/source/database-config.dist.php src/source/database-config.php
        cp src/source/.dist.htaccess src/source/.htaccess
        cd src/source
        composer install --ignore-platform-reqs
        cd ..
        composer install
        npm install

2. Configure following files:

        src/source/config.php ENVIRONMENTBASEURL :
                https://robbie.dev.matrix.msu.edu/~christj2/enslaved/src/build_local/
        src/source/config.php ENVIRONMENTBASEPATH :
                /home/christj2/website/enslaved/src/build_local/
        src/source/config.php TOKEN :
                Copy from kora.enslaved.org
        src/source/.htaccess ENVIRONMENTBASEPATH :
                /~christj2/enslaved/src/build_local/


3. To compile scss and build site, run:

        npm run dev

Site will build at `/build_local`

#### Update the live site instructions
```
cd ~/enslaved/src/
npm run dev
rm -rf ~/website/*
cp -r ~/enslaved/src/build_local/* ~/website/
```
