#!/usr/bin/env bash

# Path to ASCII Art logo
FILE=${PWD}/vendor/bin/msbios.sh;

# Show ASCII Art if is it perhaps
if [ -f $FILE ]; then
    bash $FILE
fi

echo 'All containers are done'
