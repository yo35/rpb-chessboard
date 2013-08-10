#!/bin/sh

POT_FILE=languages/rpbchessboard.pot

xgettext --language=PHP -k__ -k_e -o $POT_FILE `find . -name '*.php'`
