################################################################################
#                                                                              #
#    This file is part of RPB Chessboard, a WordPress plugin.                  #
#    Copyright (C) 2013-2025  Yoann Le Montagner <yo35 -at- melix.net>         #
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


# Files by type
PHP_FILES          = rpb-chessboard.php $(shell find php -name '*.php')
I18N_POT_FILE      = languages/rpb-chessboard.pot
I18N_PO_FILES      = $(wildcard languages/*.po)
I18N_MO_FILES      = $(patsubst %.po,%.mo,$(I18N_PO_FILES))
I18N_MERGED_FILES  = $(patsubst %.po,%.merged,$(I18N_PO_FILES))


# Various commands
ECHO          = echo
TOUCH         = touch
GETTEXT_PHP   = ./scripts/gettext-php.sh
MSGMERGE      = msgmerge -v --backup=none
MSGFMT        = msgfmt -v
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
	@$(ECHO) " * make $(COLOR_ITEM_IN)help$(COLOR_ITEM_OUT): show this help."
	@$(ECHO) " * make $(COLOR_ITEM_IN)i18n-extract$(COLOR_ITEM_OUT): extract the strings to translate."
	@$(ECHO) " * make $(COLOR_ITEM_IN)i18n-merge$(COLOR_ITEM_OUT): merge the translation files (*.po) with the template (.pot)."
	@$(ECHO) " * make $(COLOR_ITEM_IN)i18n-compile$(COLOR_ITEM_OUT): compile the translation files (*.po) into binaries (*.mo)."
	@$(ECHO) " * make $(COLOR_ITEM_IN)clean$(COLOR_ITEM_OUT): remove the automatically generated files."
	@$(ECHO)


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
%.merged: %.po $(I18N_POT_FILE)
	@$(ECHO) "$(COLOR_IN)Updating PO file [ $(COLOR_ARG_IN)$<$(COLOR_ARG_OUT) ]...$(COLOR_OUT)"
	@$(MSGMERGE) -U $^
	@mkdir -p `dirname $@`


# PO file compilation
%.mo: %.po
	@$(ECHO) "$(COLOR_IN)Compiling MO file [ $(COLOR_ARG_IN)$@$(COLOR_ARG_OUT) ]...$(COLOR_OUT)"
	@$(MSGFMT) -o $@ $^


# Clean the automatically generated files
clean:
	@rm -rf $(I18N_MO_FILES)


# Make's stuff
.PHONY: help i18n-extract i18n-merge i18n-compile clean
