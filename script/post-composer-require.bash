#!/usr/bin/env bash
#
# Install Game Overflow into an Anax installation using a default setup.
#
rsync -av vendor/pamo18/ramverk1-project/config/ config/
rsync -av vendor/pamo18/ramverk1-project/content/ content/
rsync -av vendor/pamo18/ramverk1-project/htdocs/ htdocs/
rsync -av vendor/pamo18/ramverk1-project/src/ src/
rsync -av vendor/pamo18/ramverk1-project/test/Controller/ test/Controller/
rsync -av vendor/pamo18/ramverk1-project/view/ view/

# Setup the sqlite3 database with reset scripts
mkdir script
rsync -av vendor/pamo18/ramverk1-project/script/reset_db.bash script/
rsync -av vendor/pamo18/ramverk1-project/script/reset_testdb.bash script/

mkdir sql
rsync -av vendor/pamo18/ramverk1-project/sql/ sql/

mkdir data
bash vendor/pamo18/ramverk1-project/script/reset_db.bash
bash vendor/pamo18/ramverk1-project/script/reset_testdb.bash
