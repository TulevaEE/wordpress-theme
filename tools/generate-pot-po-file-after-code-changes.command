#!/bin/bash
SCRIPT=$(realpath "$0")
SCRIPTPATH=$(dirname "$SCRIPT")
bold=$(tput bold)
normal=$(tput sgr0)

cd $SCRIPTPATH/..; sh tools/i18n/generate-pot.sh;
cd $SCRIPTPATH/..; sh tools/i18n/generate-po.sh;
echo "####################################################################"
echo "${bold}Generated .pot and .po translation files (after code changes)"
echo "After editing .po file, compile .mo file and replace it with POEditor${normal}"
echo "####################################################################"

if open -Ra "Poedit" ; then
  open $SCRIPTPATH/../src/wp-content/themes/tuleva/lang/et.po
fi

osascript -e 'tell application "Terminal" to close first window' & exit