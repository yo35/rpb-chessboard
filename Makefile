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


# Plugin name ("no space" lower case version)
PLUGIN_NAME = rpb-chessboard

# Plugin files
SRC_MAIN_FILE         = $(PLUGIN_NAME).php
SRC_FOLDERS           = css helpers images js languages models templates wp
BUILD_FOLDER          = build
THIRD_PARTY_FOLDER    = third-party-libs
ASSET_FOLDER          = assets
WORDPRESS_README_FILE = wordpress.readme.txt
INFO_FILES            = LICENSE examples.pgn
PACKAGE_JSON_FILE     = package.json
NPM_TEMPLATE_FILE     = src/index.js


# Zip file used for deployment
DEPLOYMENT_FILE = $(PLUGIN_NAME).zip

# Localization
I18N_TEXT_DOMAIN = rpb-chessboard


# Files by type
PHP_FILES          = $(SRC_MAIN_FILE) $(shell find $(SRC_FOLDERS) -name '*.php')
JS_FILES           = $(shell find js -name '*.js' -not -name '*.min.js')
JS_MINIFIED_FILES  = $(patsubst %.js,%.min.js,$(JS_FILES))
CSS_FILES          = $(shell find css -name '*.css' -not -name '*.min.css')
CSS_MINIFIED_FILES = $(patsubst %.css,%.min.css,$(CSS_FILES))
I18N_POT_FILE      = languages/$(I18N_TEXT_DOMAIN).pot
I18N_PO_FILES      = $(wildcard languages/*.po)
I18N_MO_FILES      = $(patsubst %.po,%.mo,$(I18N_PO_FILES))


# Temporary objects
NODE_MODULES      = node_modules
TEMPORARY_FOLDER  = .temp
SNAPSHOT_FOLDER   = $(TEMPORARY_FOLDER)/snapshot
I18N_MERGED_FILES = $(patsubst %.po,$(TEMPORARY_FOLDER)/%.merged,$(I18N_PO_FILES))


# Various commands
ECHO          = echo
TOUCH         = touch
GETTEXT_PHP   = ./assets/dev-tools/gettext-php.sh
MSGMERGE      = msgmerge -v --backup=none
MSGFMT        = msgfmt -v
ESLINT        = ./node_modules/.bin/eslint
UGLIFYJS      = ./node_modules/.bin/uglifyjs -c
UGLIFYCSS     = ./node_modules/.bin/uglifycss
PHPCS         = phpcs --colors --standard=assets/dev-tools/phpcs.xml
PHPCBF        = phpcbf --standard=assets/dev-tools/phpcs.xml
COLOR_IN      = \033[34;1m
COLOR_OUT     = \033[0m
COLOR_ARG_IN  = \033[31m
COLOR_ARG_OUT = \033[34m
COLOR_ITEM_IN = \033[35;1m
COLOR_ITEM_OUT= \033[0m


# Help notice
all: help
help:
	@$(ECHO)
	@$(ECHO) "$(COLOR_IN)Available commands:$(COLOR_OUT)"
	@$(ECHO) " * make $(COLOR_ITEM_IN)init$(COLOR_ITEM_OUT): initialize the repository for development."
	@$(ECHO) " * make $(COLOR_ITEM_IN)i18n-extract$(COLOR_ITEM_OUT): extract the strings to translate."
	@$(ECHO) " * make $(COLOR_ITEM_IN)i18n-merge$(COLOR_ITEM_OUT): merge the translation files (*.po) with the template (.pot)."
	@$(ECHO) " * make $(COLOR_ITEM_IN)i18n-compile$(COLOR_ITEM_OUT): compile the translation files (*.po) into binaries (*.mo)."
	@$(ECHO) " * make $(COLOR_ITEM_IN)eslint$(COLOR_ITEM_OUT): run the static analysis of JavaScript files."
	@$(ECHO) " * make $(COLOR_ITEM_IN)jsmin$(COLOR_ITEM_OUT): run the JavaScript minifier tool on JavaScript files."
	@$(ECHO) " * make $(COLOR_ITEM_IN)cssmin$(COLOR_ITEM_OUT): run the CSS minifier tool on CSS files."
	@$(ECHO) " * make $(COLOR_ITEM_IN)phpcs$(COLOR_ITEM_OUT): run the static analysis of PHP files."
	@$(ECHO) " * make $(COLOR_ITEM_IN)phpcbf$(COLOR_ITEM_OUT): try to fix some of the errors detected by static analysis on PHP files."
	@$(ECHO) " * make $(COLOR_ITEM_IN)pack$(COLOR_ITEM_OUT): pack the source files into a zip file, ready for WordPress deployment."
	@$(ECHO) " * make $(COLOR_ITEM_IN)clean$(COLOR_ITEM_OUT): remove the automatically generated files."
	@$(ECHO) " * make $(COLOR_ITEM_IN)help$(COLOR_ITEM_OUT): show this help."
	@$(ECHO)




################################################################################
# Initialization
################################################################################


init: $(NODE_MODULES) $(BUILD_FOLDER)

$(BUILD_FOLDER): $(NPM_TEMPLATE_FILE) $(PACKAGE_JSON_FILE)
	@$(ECHO) "$(COLOR_IN)Generating main JS file...$(COLOR_OUT)"
	@npm run build

$(NODE_MODULES): $(PACKAGE_JSON_FILE)
	@$(ECHO) "$(COLOR_IN)Installing NPM modules...$(COLOR_OUT)"
	@npm install




################################################################################
# Localization targets
################################################################################


# Extract the strings to translate
i18n-extract: $(I18N_POT_FILE)


# Merge the translation files (*.po) with the template (.pot)
i18n-merge: $(I18N_MERGED_FILES)


# Compile the translation files (*.po) into binaries (*.mo)
i18n-compile: $(I18N_MO_FILES)


# POT file generation
$(I18N_POT_FILE): $(PHP_FILES)
	@$(ECHO) "$(COLOR_IN)Updating the translation template file...$(COLOR_OUT)"
	@$(GETTEXT_PHP) $@ $^


# PO and POT file merging
$(TEMPORARY_FOLDER)/%.merged: %.po $(I18N_POT_FILE)
	@$(ECHO) "$(COLOR_IN)Updating PO file [ $(COLOR_ARG_IN)$<$(COLOR_ARG_OUT) ]...$(COLOR_OUT)"
	@$(MSGMERGE) -U $^
	@mkdir -p `dirname $@`
	@$(TOUCH) $@


# PO file compilation
%.mo: %.po
	@$(ECHO) "$(COLOR_IN)Compiling MO file [ $(COLOR_ARG_IN)$@$(COLOR_ARG_OUT) ]...$(COLOR_OUT)"
	@$(MSGFMT) -o $@ $^




################################################################################
# JavaScript targets
################################################################################


# JavaScript validation
eslint: $(NODE_MODULES)
	@$(ECHO) "$(COLOR_IN)Checking the JavaScript files...$(COLOR_OUT)"
	@$(ESLINT) $(JS_FILES)


# JavaScript minification
jsmin: $(JS_MINIFIED_FILES) $(NPM_DEPS_MIN_FILE)


# Single JS file minification
%.min.js: %.js $(NODE_MODULES)
	@$(ECHO) "$(COLOR_IN)Minifying JS file [ $(COLOR_ARG_IN)$<$(COLOR_ARG_OUT) ]...$(COLOR_OUT)"
	@$(UGLIFYJS) -o $@ $<




################################################################################
# CSS targets
################################################################################


# CSS minification
cssmin: $(CSS_MINIFIED_FILES)


# Single CSS file minification
%.min.css: %.css $(NODE_MODULES)
	@$(ECHO) "$(COLOR_IN)Minifying CSS file [ $(COLOR_ARG_IN)$<$(COLOR_ARG_OUT) ]...$(COLOR_OUT)"
	@$(UGLIFYCSS) --output $@ $<




################################################################################
# PHP targets
################################################################################


# PHP validation
phpcs:js
	@$(ECHO) "$(COLOR_IN)Checking the PHP files...$(COLOR_OUT)"
	@$(PHPCS) $(PHP_FILES)


# PHP autofix
phpcbf:
	@$(ECHO) "$(COLOR_IN)Fixing the PHP files...$(COLOR_OUT)"
	@$(PHPCBF) $(PHP_FILES)




################################################################################
# Other targets
################################################################################


# Pack the source files into a zip file, ready for WordPress deployment
pack: init phpcs eslint i18n-compile jsmin cssmin
	@rm -rf $(SNAPSHOT_FOLDER) $(DEPLOYMENT_FILE)
	@mkdir -p $(SNAPSHOT_FOLDER)/$(PLUGIN_NAME)
	@cp -r $(SRC_MAIN_FILE) $(SRC_FOLDERS) $(THIRD_PARTY_FOLDER) $(BUILD_FOLDER) $(INFO_FILES) $(SNAPSHOT_FOLDER)/$(PLUGIN_NAME)
	@cp $(WORDPRESS_README_FILE) $(SNAPSHOT_FOLDER)/$(PLUGIN_NAME)/readme.txt
	@mkdir -p $(SNAPSHOT_FOLDER)/$(PLUGIN_NAME)-assets
	@cp $(ASSET_FOLDER)/*.png $(SNAPSHOT_FOLDER)/$(PLUGIN_NAME)-assets
	@cd $(SNAPSHOT_FOLDER) && zip -qr ../../$(DEPLOYMENT_FILE) $(PLUGIN_NAME) $(PLUGIN_NAME)-assets
	@$(ECHO) "$(COLOR_IN)$(DEPLOYMENT_FILE) updated$(COLOR_OUT)"


# Clean the automatically generated files
clean:
	@rm -rf $(NODE_MODULES) $(TEMPORARY_FOLDER) $(DEPLOYMENT_FILE) $(JS_MINIFIED_FILES) $(CSS_MINIFIED_FILES) $(I18N_MO_FILES) $(BUILD_FOLDER)


# Make's stuff
.PHONY: help init i18n-extract i18n-merge i18n-compile eslint jsmin cssmin phpcs phpcbf pack clean
