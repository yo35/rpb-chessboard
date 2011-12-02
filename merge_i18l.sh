#!/bin/sh

if [ "$#" -eq "0" ]; then
	echo 'usage: ./merge_i18l.sh [locale]'
	echo 'ex: ./merge_i18l.sh fr_FR'
	exit
fi

msgmerge -v -U languages/rpbchessboard-$1.po languages/rpbchessboard.pot
rm languages/*.po~
