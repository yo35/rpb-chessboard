#!/bin/bash
# Script to prepare a plugin release

PLUGIN_NAME=rpbchessboard
OUTPUT_FOLDER=../$PLUGIN_NAME-release
SOURCE_FILES=(controllers css helpers images js languages models templates views *.php)

# Remove the previous released folder, if it exists.
rm -rf $OUTPUT_FOLDER

# Export the plugin files
mkdir $OUTPUT_FOLDER
cp -r ${SOURCE_FILES[*]} $OUTPUT_FOLDER

# Special third-party libs
mkdir $OUTPUT_FOLDER/chess-js
cp -r chess-js/* $OUTPUT_FOLDER/chess-js

