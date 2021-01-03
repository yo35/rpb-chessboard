#!/bin/bash
################################################################################
#                                                                              #
#    This file is part of RPB Chessboard, a WordPress plugin.                  #
#    Copyright (C) 2013-2021  Yoann Le Montagner <yo35 -at- melix.net>         #
#                                                                              #
#    This program is free software: you can redistribute it and/or modify      #
#    it under the terms of the GNU General Public License as published by      #
#    the Free Software Foundation, either version 3 of the License, or         #
#    (at your option) any later version.                                       #
#                                                                              #
#    This program is distributed in the hope that it will be useful,           #
#    but WITHOUT ANY WARRANTY; without even the implied warranty of            #
#    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the             #
#    GNU General Public License for more details.                              #
#                                                                              #
#    You should have received a copy of the GNU General Public License         #
#    along with this program.  If not, see <http://www.gnu.org/licenses/>.     #
#                                                                              #
################################################################################


# Check arguments
if [ $# -lt 2 ]; then
	echo "Usage: $0 <output-pot-file> <main-php-file> [<php-file-1>] [<php-file-2>] ..."
	exit 1
fi


# In/out files
OUTPUT_POT_FILE=$1
MAIN_PHP_FILE=$2
shift
ALL_PHP_FILES=$*


# Translator keyword
I18N_TRANSLATOR_KEYWORD=i18n


# Parse the PHP files
xgettext --from-code=UTF-8 --language=PHP -c$I18N_TRANSLATOR_KEYWORD -k__ -k_e -kesc_html__ -kesc_html_e -kesc_attr__ -kesc_attr_e -o $OUTPUT_POT_FILE $ALL_PHP_FILES

# Extract the plugin description from the WordPress plugin header
sed -n -e "s/^Description: *\(.*\)/\n#: $MAIN_PHP_FILE\nmsgid \"\1\"\nmsgstr \"\"/p" $MAIN_PHP_FILE >> $OUTPUT_POT_FILE

# Cleanup .pot file
sed -i -e "s/^#\. *$I18N_TRANSLATOR_KEYWORD *\(.*\)/#. \1/" $OUTPUT_POT_FILE
sed -i -e '/^#:/ { s/:[1-9][0-9]*//g }' $OUTPUT_POT_FILE
