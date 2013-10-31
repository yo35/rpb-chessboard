################################################################################
#                                                                              #
#    This file is part of RPB Chessboard, a Wordpress plugin.                  #
#    Copyright (C) 2013  Yoann Le Montagner <yo35 -at- melix.net>              #
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


# Plugin name ("no space" lower case version)
PLUGIN_NAME = rpb-chessboard

# Plugin files
SRC_FOLDERS          = chess-js controllers css helpers images js languages models templates views
SRC_PHP_FILES        = $(wildcard *.php)
SRC_WORDPRESS_README = wordpress.readme.txt
SRC_SPECIAL_FILES    = LICENSE README.md $(wildcard screenshot-*.png)

# Do not modify
TMP_FOLDER       = tmp
SNAPSHOT_FOLDER  = $(TMP_FOLDER)/$(PLUGIN_NAME)
SNAPSHOT_ARCHIVE = $(PLUGIN_NAME).zip

# Various commands
ECHO = echo

# Help notice
all: help
help:
	@$(ECHO) "Available commands:"
	@$(ECHO) " * make pack: pack the source files into a zip file, ready for Wordpress deployment."
	@$(ECHO) " * make help: show this help."

# Pack the source files into a zip file, ready for Wordpress deployment
pack:
	@rm -rf $(SNAPSHOT_FOLDER) $(SNAPSHOT_ARCHIVE)
	@mkdir -p $(SNAPSHOT_FOLDER)
	@cp -r $(SRC_FOLDERS) $(SRC_PHP_FILES) $(SRC_SPECIAL_FILES) $(SNAPSHOT_FOLDER)
	@cp $(SRC_WORDPRESS_README) $(SNAPSHOT_FOLDER)/readme.txt
	@cd $(TMP_FOLDER) && zip -qr ../$(SNAPSHOT_ARCHIVE) $(PLUGIN_NAME)
	@rm -rf $(TMP_FOLDER)
	@$(ECHO) "$(SNAPSHOT_ARCHIVE) updated"

# Make's stuff
.PHONY: pack help
