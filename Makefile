################################################################################
#                                                                              #
#    This file is part of RPB Chessboard, a Wordpress plugin.                  #
#    Copyright (C) 2013-2014  Yoann Le Montagner <yo35 -at- melix.net>         #
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
SRC_FOLDERS          = chess-js controllers css helpers images js languages models templates views wp
SRC_PHP_FILES        = $(wildcard *.php)
SRC_WORDPRESS_README = wordpress.readme.txt
SRC_SPECIAL_FILES    = LICENSE README.md $(wildcard screenshot-*.png)

# Localization
I18L_LANGUAGE_FOLDER    = languages
I18L_TEXT_DOMAIN        = rpbchessboard
I18L_TRANSLATOR_KEYWORD = i18l
I18L_SOURCE_FILES       = $(shell find . -name '*.php')
I18L_MAIN_SOURCE_FILE   = $(PLUGIN_NAME).php
I18L_POT_FILE           = $(I18L_LANGUAGE_FOLDER)/$(I18L_TEXT_DOMAIN).pot
I18L_PO_FILES           = $(wildcard $(I18L_LANGUAGE_FOLDER)/*.po)
I18L_MO_FILES           = $(patsubst %.po,%.mo,$(I18L_PO_FILES))

# Do not modify
TMP_FOLDER       = tmp
SNAPSHOT_FOLDER  = $(TMP_FOLDER)/$(PLUGIN_NAME)
SNAPSHOT_ARCHIVE = $(PLUGIN_NAME).zip

# Various commands
ECHO     = echo
SED      = sed
TOUCH    = touch
XGETTEXT = xgettext --from-code=UTF-8 --language=PHP -c$(I18L_TRANSLATOR_KEYWORD) -k__ -k_e
MSGMERGE = msgmerge -v
MSGFMT   = msgfmt -v


# Help notice
all: help
help:
	@$(ECHO) "Available commands:"
	@$(ECHO) " * make i18l-extract: extract the strings to translate."
	@$(ECHO) " * make i18l-merge: merge the translation files (*.po) with the template (.pot)."
	@$(ECHO) " * make i18l-compile: compile the translation files (*.po) into binaries (*.mo)."
	@$(ECHO) " * make pack: pack the source files into a zip file, ready for Wordpress deployment."
	@$(ECHO) " * make help: show this help."


# Localization: extract the strings to translate
i18l-extract: $(I18L_POT_FILE)

# Localization: merge the translation files (*.po) with the template (.pot)
i18l-merge: $(I18L_PO_FILES)

# Localization: compile the translation files (*.po) into binaries (*.mo)
i18l-compile: $(I18L_MO_FILES)


# POT file generation
$(I18L_POT_FILE): $(I18L_SOURCE_FILES)
	@$(ECHO) "Updating file $@..."
	@$(XGETTEXT) -o $@ $^
	@$(SED) -n -e "s/^Description: *\(.*\)/\n#: $(I18L_MAIN_SOURCE_FILE)\nmsgid \"\1\"\nmsgstr \"\"/p" $(I18L_MAIN_SOURCE_FILE) >> $@
	@$(SED) -i -e "s/^#\. *$(I18L_TRANSLATOR_KEYWORD) *\(.*\)/#. \1/" $@

# PO and POT file merging
%.po: $(I18L_POT_FILE)
	@$(ECHO) "Merging file $@..."
	@$(MSGMERGE) -U $@ $^
	@$(TOUCH) $@
	@rm -f $(I18L_LANGUAGE_FOLDER)/*.po~

# PO file compilation
%.mo: %.po
	@$(ECHO) "Compiling file $@..."
	@$(MSGFMT) -o $@ $^


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
.PHONY: i18l-extract i18l-merge i18l-compile pack help
