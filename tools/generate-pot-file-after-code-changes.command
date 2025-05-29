#!/bin/bash
SCRIPT=$(realpath "$0")
SCRIPTPATH=$(dirname "$SCRIPT")
bold=$(tput bold)
normal=$(tput sgr0)

cd $SCRIPTPATH/..; sh tools/i18n/generate-pot.sh;
#cd $SCRIPTPATH/..; sh tools/i18n/generate-po.sh;
echo "###############################################"
echo "${bold}Generated .pot file (after code changes)"
echo "###############################################"

if open -Ra "Poedit" ; then
  open $SCRIPTPATH/../src/wp-content/themes/tuleva/lang/et.po
fi

osascript -e 'tell application "Terminal" to close first window' & exit