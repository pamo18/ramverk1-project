#!/usr/bin/env bash
#
# Install Game Overflow into an Anax installation using a default setup.
#
rsync -av vendor/pamo18/ramverk1-project/config/ config/
rsync -av vendor/pamo18/ramverk1-project/content/ content/
rsync -av vendor/pamo18/ramverk1-project/htdocs/ htdocs/
rsync -av vendor/pamo18/ramverk1-project/src/ src/
rsync -av vendor/pamo18/ramverk1-project/test/Controller test/Controller/
rsync -av vendor/pamo18/ramverk1-project/view/ view/

mkdir data
vendor/pamo18/ramverk1-project/script/reset_db.bash
