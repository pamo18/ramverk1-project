#!/usr/bin/env bash
#
# Install Weather service into an Anax installation using a default setup.
#
rsync -av vendor/pamo18/ramverk1-project/config/ config/
rsync -av vendor/pamo18/ramverk1-project/content/ content/
rsync -av vendor/pamo18/ramverk1-project/htdocs/ htdocs/
rsync -av vendor/pamo18/ramverk1-project/src/ src/
rsync -av vendor/pamo18/ramverk1-project/test/GeoTag test/
rsync -av vendor/pamo18/ramverk1-project/test/IPGeotag test/
rsync -av vendor/pamo18/ramverk1-project/test/IPValidate test/
rsync -av vendor/pamo18/ramverk1-project/test/Weather test/
