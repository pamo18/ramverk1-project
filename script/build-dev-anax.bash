#!/usr/bin/env bash
#
# Setup a test environment for the module, this script is to be
# called from the makefile using `make bild-anax` which installs
# a development version av anax in build/anax using this module.
#

#
# Exit with an error message
# $1 the message to display
# $2 an optinal exit code, default is 1
#
function error_exit {
    echo "$1" >&2
    exit "${2:-1}"
}

# Check Makefile is available and we are in the root of the repo
[ ! -f Makefiles ] || \
    error_exit "Missing file 'Makefile', are you really executing this script from the root of the repo?"

# Install Anax CLI
curl https://raw.githubusercontent.com/canax/anax-cli/master/src/install.bash | bash

# Scaffold an anax development installation
anax create anax ramverk1-me-v2
cd anax || \
    error_exit "Dir for anax installation was not created."

composer require pamo18/ramverk1-weather
vendor/pamo18/ramverk1-weather/script/post-composer-require.bash
