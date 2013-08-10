#!/bin/sh

if [ "$#" -eq "0" ]; then
	echo 'usage: ./merge_i18l.sh [locale]'
	echo 'ex: ./merge_i18l.sh fr_FR'
	exit
fi

PO_PREFIX=languages/rpbchessboard
POT_FILE=languages/rpbchessboard.pot

msgmerge -v -U $PO_PREFIX-$1.po $POT_FILE
rm languages/*.po~
