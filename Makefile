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
SRC_FOLDERS          = chess-js controllers css fonts helpers images js languages models templates views wp
SRC_MAIN_FILE        = $(PLUGIN_NAME).php
SRC_ASSETS           = assets
SRC_WORDPRESS_README = wordpress.readme.txt
SRC_INFO_FILES       = LICENSE README.md

# Files by type
JS_FILES  = $(shell find js -name '*.js')
PHP_FILES = $(shell find . -name '*.php')

# Localization
I18N_LANGUAGE_FOLDER    = languages
I18N_TEXT_DOMAIN        = rpbchessboard
I18N_TRANSLATOR_KEYWORD = i18n
I18N_POT_FILE           = $(I18N_LANGUAGE_FOLDER)/$(I18N_TEXT_DOMAIN).pot
I18N_PO_FILES           = $(wildcard $(I18N_LANGUAGE_FOLDER)/*.po)
I18N_MO_FILES           = $(patsubst %.po,%.mo,$(I18N_PO_FILES))

# Do not modify
SNAPSHOT_FOLDER  = tmp
SNAPSHOT_ARCHIVE = $(PLUGIN_NAME).zip

# Various commands
ECHO      = echo
SED       = sed
TOUCH     = touch
XGETTEXT  = xgettext --from-code=UTF-8 --language=PHP -c$(I18N_TRANSLATOR_KEYWORD) -k__ -k_e
MSGMERGE  = msgmerge -v
MSGFMT    = msgfmt -v
JSHINT    = jshint
COLOR_IN  = \033[34;1m
COLOR_OUT = \033[0m

# Help notice
all: help
help:
	@$(ECHO) "Available commands:"
	@$(ECHO) " * make i18n-extract: extract the strings to translate."
	@$(ECHO) " * make i18n-merge: merge the translation files (*.po) with the template (.pot)."
	@$(ECHO) " * make i18n-compile: compile the translation files (*.po) into binaries (*.mo)."
	@$(ECHO) " * make lint-js: run the static analysis of JavaScript files."
	@$(ECHO) " * make pack: pack the source files into a zip file, ready for WordPress deployment."
	@$(ECHO) " * make help: show this help."


# Internationalization: extract the strings to translate
i18n-extract: $(I18N_POT_FILE)

# Internationalization: merge the translation files (*.po) with the template (.pot)
i18n-merge: $(I18N_PO_FILES)

# Internationalization: compile the translation files (*.po) into binaries (*.mo)
i18n-compile: $(I18N_MO_FILES)


# POT file generation
$(I18N_POT_FILE): $(PHP_FILES)
	@$(ECHO) "$(COLOR_IN)Updating file $@...$(COLOR_OUT)"
	@$(XGETTEXT) -o $@ $^
	@$(SED) -n -e "s/^Description: *\(.*\)/\n#: $(SRC_MAIN_FILE)\nmsgid \"\1\"\nmsgstr \"\"/p" $(SRC_MAIN_FILE) >> $@
	@$(SED) -i -e "s/^#\. *$(I18N_TRANSLATOR_KEYWORD) *\(.*\)/#. \1/" $@


# PO and POT file merging
%.po: $(I18N_POT_FILE)
	@$(ECHO) "$(COLOR_IN)Merging file $@...$(COLOR_OUT)"
	@$(MSGMERGE) -U $@ $^
	@$(TOUCH) $@
	@rm -f $(I18N_LANGUAGE_FOLDER)/*.po~


# PO file compilation
%.mo: %.po
	@$(ECHO) "$(COLOR_IN)Compiling file $@...$(COLOR_OUT)"
	@$(MSGFMT) -o $@ $^


# JavaScript validation
lint-js:
	@$(ECHO) "$(COLOR_IN)Checking the JavaScript files...$(COLOR_OUT)"
	@$(JSHINT) $(JS_FILES)


# Pack the source files into a zip file, ready for WordPress deployment
pack: lint-js
	@rm -rf $(SNAPSHOT_FOLDER) $(SNAPSHOT_ARCHIVE)
	@mkdir -p $(SNAPSHOT_FOLDER)/$(PLUGIN_NAME)
	@cp -r $(SRC_FOLDERS) $(SRC_MAIN_FILE) $(SRC_INFO_FILES) $(SNAPSHOT_FOLDER)/$(PLUGIN_NAME)
	@cp $(SRC_WORDPRESS_README) $(SNAPSHOT_FOLDER)/$(PLUGIN_NAME)/readme.txt
	@cp -r $(SRC_ASSETS) $(SNAPSHOT_FOLDER)/$(PLUGIN_NAME)-assets
	@cd $(SNAPSHOT_FOLDER) && zip -qr ../$(SNAPSHOT_ARCHIVE) $(PLUGIN_NAME) $(PLUGIN_NAME)-assets
	@rm -rf $(SNAPSHOT_FOLDER)
	@$(ECHO) "$(COLOR_IN)$(SNAPSHOT_ARCHIVE) updated$(COLOR_OUT)"


# Make's stuff
.PHONY: help i18n-extract i18n-merge i18n-compile lint-js pack
