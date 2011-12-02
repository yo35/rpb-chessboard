#!/bin/sh

if [ "$#" -eq "0" ]; then
	echo 'usage: ./compile_i18l.sh [locale]'
	echo 'ex: ./compile_i18l.sh fr_FR'
	exit
fi

msgfmt -v -o languages/rpbchessboard-$1.mo languages/rpbchessboard-$1.po
