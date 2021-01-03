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


# Source files
FILES=$*

# Number of files
FILE_COUNT=`echo $FILES | wc -w`

# Total number of lines
ALL_LINE_COUNT=`cat $FILES | wc -l`

# Empty lines
EMPTY_LINE_COUNT=`cat $FILES | sed -n -e '/^\s*$/p' | wc -l`

# Comment lines
COMMENT_LINE_COUNT_1=`cat $FILES | sed -n -e '/^\s*\/\/.*$/p' | wc -l`                               # //comment-style
COMMENT_LINE_COUNT_2=`cat $FILES | sed -n -e '/^\s*\/\*.*\*\/.*$/p' | wc -l`                         # /*comment*/-style
COMMENT_LINE_COUNT_3=`cat $FILES | sed -n -e '/^\s*\/\*.*\*\/.*$/d' -e '/^\s*\/\*/,/\*\//p' | wc -l` # /*comment*/-style spanning several lines
COMMENT_LINE_COUNT=`expr $COMMENT_LINE_COUNT_1 + $COMMENT_LINE_COUNT_2 + $COMMENT_LINE_COUNT_3`

# Code lines
CODE_LINE_COUNT=`expr $ALL_LINE_COUNT - $EMPTY_LINE_COUNT - $COMMENT_LINE_COUNT`
COMMENT_CODE_RATIO=`expr $COMMENT_LINE_COUNT '*' 100 / $CODE_LINE_COUNT`

# Print the result
echo -e "Source files:       $FILE_COUNT"
echo -e "Code lines:         $CODE_LINE_COUNT"
echo -e "Comment lines:      $COMMENT_LINE_COUNT"
echo -e "Ratio comment/code: $COMMENT_CODE_RATIO %"
