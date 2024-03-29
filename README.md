## Enslaved Hub / Static Site Builder

### Installation instructions for development
1. Copy script to terminal:

        git clone git@direct.gitlab.matrix.msu.edu:matrix/enslaved.git
        cd enslaved
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

#### Rebuild visualization counts instructions
```
cd ~/website/enslaved/src/source/visualizeCounts
rm latest.wikibase.dump.json
wget https://manta.matrix.msu.edu/msumatrix/public/exports/wikibase/2bde7b5b/latest.wikibase.dump.json.gz
gzip -d latest.wikibase.dump.json.gz
php script.php
cd ~/website/enslaved/src
npm run dev
```
