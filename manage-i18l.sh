#!/bin/sh
# Useful script to manage the localization in a Wordpress plugin project

PLUGIN_NAME=rpbchessboard
LANGUAGE_FOLDER=languages
POT_FILE=$LANGUAGE_FOLDER/$PLUGIN_NAME.pot
PO_PREFIX=$LANGUAGE_FOLDER/$PLUGIN_NAME
MO_PREFIX=$LANGUAGE_FOLDER/$PLUGIN_NAME

# First argument: action
if [ "$#" -eq "0" ]; then
	echo 'Usage: ./manage-i18l.sh <command> ...'
	echo 'The available commands are:'
	echo '  extract -> Extract the localized strings from the PHP files and generate the template POT file.'
	echo '  merge   -> Merge a PO file containing translated strings to the template POT file.'
	echo '  compile -> Compile a PO file into a binary MO file.'
	exit
fi

COMMAND=$1



# Command 'extract'
if [ "$COMMAND" = "extract" ]; then
	
	# Check the argument
	if [ "$#" -eq "1" ]; then
		echo 'Usage: ./manage-i18l.sh extract <path_to_makepot.php>'
		exit
	fi
	
	# Do the job
	php $2 wp-plugin .
	mv $PLUGIN_NAME.pot $POT_FILE
	exit
fi



# Command 'merge'
if [ "$COMMAND" = "merge" ]; then
	
	# Check the argument
	if [ "$#" -eq "1" ]; then
		echo 'Usage: ./manage-i18l.sh merge <locale>'
		echo 'Example: ./manage-i18l.sh merge fr_FR'
		exit
	fi
	
	# Do the job
	msgmerge -v -U $PO_PREFIX-$2.po $POT_FILE
	rm $LANGUAGE_FOLDER/*.po~
	exit
fi



# Command 'compile'
if [ "$COMMAND" = "compile" ]; then
	
	# Check the argument
	if [ "$#" -eq "1" ]; then
		echo 'Usage: ./manage-i18l.sh compile <locale>'
		echo 'Example: ./manage-i18l.sh compile fr_FR'
		exit
	fi
	
	# Do the job
	msgfmt -v -o $MO_PREFIX-$2.mo $PO_PREFIX-$2.po
	exit
fi



# Error: unknown command
echo "Unknown command: $COMMAND"
