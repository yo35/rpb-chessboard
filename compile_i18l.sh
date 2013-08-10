#!/bin/sh

if [ "$#" -eq "0" ]; then
	echo 'usage: ./compile_i18l.sh [locale]'
	echo 'ex: ./compile_i18l.sh fr_FR'
	exit
fi

PO_PREFIX=languages/rpbchessboard

msgfmt -v -o $PO_PREFIX-$1.mo $PO_PREFIX-$1.po
