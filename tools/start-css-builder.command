#!/bin/bash
SCRIPT=$(realpath "$0")
SCRIPTPATH=$(dirname "$SCRIPT")
cd $SCRIPTPATH/../src/wp-content/themes/tuleva; npm install; npm run watch:scss;
