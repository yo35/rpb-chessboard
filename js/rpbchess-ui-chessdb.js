/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2018  Yoann Le Montagner <yo35 -at- melix.net>       *
 *                                                                            *
 *    This program is free software: you can redistribute it and/or modify    *
 *    it under the terms of the GNU General Public License as published by    *
 *    the Free Software Foundation, either version 3 of the License, or       *
 *    (at your option) any later version.                                     *
 *                                                                            *
 *    This program is distributed in the hope that it will be useful,         *
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of          *
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the           *
 *    GNU General Public License for more details.                            *
 *                                                                            *
 *    You should have received a copy of the GNU General Public License       *
 *    along with this program.  If not, see <http://www.gnu.org/licenses/>.   *
 *                                                                            *
 ******************************************************************************/


/**
 * jQuery widget to display a list of chess games.
 *
 * @requires kokopu
 * @requires jQuery
 * @requires jQuery UI Widget
 * @requires jQuery UI Selectmenu
 */
(function(kokopu, $)
{
	'use strict';
	
	
	/**
	 * Public static properties.
	 */
	$.chessdb = {

		/**
		 * CSS class for the select menu widget.
		 * @type {string}
		 */
		selectMenuClass: ''

	}; /* $.chessdb = { ... } */


	/**
	 * Ellipsis function.
	 *
	 * Example: if `text` is `0123456789`, then `ellipsis(text, 5, 2, 1)` returns
	 * the following string:
	 *
	 * ```
	 * ...4567...
	 *     ^
	 * ```
	 *
	 * @param {string} text Text from a substring must be extracted.
	 * @param {number} pos Index of the character in `text` around which the substring must be extracted.
	 * @param {number} backwardCharacters Number of characters to keep before `pos`.
	 * @param {number} forwardCharacters Number of characters to keep after `pos`.
	 * @returns {string}
	 */
	function ellipsisAt(text, pos, backwardCharacters, forwardCharacters)
	{
		// p1 => begin of the extracted sub-string
		var p1 = pos - backwardCharacters;
		var e1 = '...';
		if(p1<=0) {
			p1 = 0;
			e1 = '';
		}

		// p2 => one character after the end of the extracted sub-string
		var p2 = pos + 1 + forwardCharacters;
		var e2 = '...';
		if(p2 >= text.length) {
			p2 = text.length;
			e2 = '';
		}

		// Extract the sub-string around the requested position.
		var retVal = e1 + text.substr(p1, p2-p1) + e2;
		retVal = retVal.replace(/\n|\t/g, ' ');
		return retVal + '\n' + new Array(1 + e1.length + pos - p1).join(' ') + '^';
	}
	
	
	/**
	 * Initialize the internal URL attribute, and initiate the asynchrone PGN retrieval if necessary.
	 */
	function initializeURL(widget, url) {

		// Nothing to do if no URL is defined.
		if(typeof url !== 'string' || url === '') {
			return '';
		}

		widget.options.pgn = '';
		widget._database = null;

		$.get(url).done(function(data) {
			widget.options.pgn = initializePGN(widget, data);
			refresh(widget);
		}).fail(function() {
			widget._database = { title: $.chessgame.i18n.PGN_DOWNLOAD_ERROR_MESSAGE, message: url };
			refresh(widget);
		});

		return url;
	}


	/**
	 * Initialize the internal `kokopu.Game` object that contains the parsed PGN data.
	 *
	 * @param {rpbchess-ui.chessdb} widget
	 * @param {string} pgn
	 * @returns {string}
	 */
	function initializePGN(widget, pgn) {

		// Ensure that the input is actually a string.
		if(typeof pgn !== 'string') {
			pgn = '*';
		}

		// Trim the input.
		pgn = pgn.replace(/^\s+|\s+$/g, '');

		// Parse the input assuming a PGN format.
		try {
			widget._database = kokopu.pgnRead(pgn);
			widget._databaseIsError = false;
		}
		catch(error) {
			if(error instanceof kokopu.exception.InvalidPGN) { // Parsing errors are reported to the user.
				widget._database = error;
				widget._databaseIsError = true;
			}
			else { // Unknown exceptions are re-thrown.
				widget._database = null;
				widget._databaseIsError = false;
				throw error;
			}
		}

		// Return the validated PGN string.
		return pgn;
	}
	
	
	
	// ---------------------------------------------------------------------------
	// Widget rendering
	// ---------------------------------------------------------------------------
	
	/**
	 * Destroy the widget content, prior to a refresh or a widget destruction.
	 *
	 * @param {rpbchess-ui.chessdb} widget
	 */
	function destroyContent(widget) {
		widget.element.empty();
	}
	
	
	/**
	 * Build the error message resulting from a PGN parsing error. TODO fix CSS classes
	 *
	 * @param {rpbchess-ui.chessdb} widget
	 * @returns {Element}
	 */
	function buildErrorMessage(widget) {

		// Build the error report box.
		var result = buildElement('div', 'rpbui-chessgame-error');
		result.appendChild(buildTextElement('div', 'rpbui-chessgame-errorTitle', widget._database instanceof kokopu.exception.InvalidPGN ?
			$.chessgame.i18n.PGN_PARSING_ERROR_MESSAGE : widget._database.title));

		// Optional message.
		if(widget._database.message !== null) {
			result.appendChild(buildTextElement('div', 'rpbui-chessgame-errorMessage', widget._database.message));
		}

		// Display where the error has occurred.
		if(widget._database.index !== null && widget._database.index >= 0) {
			var errorAt = buildElement('div', 'rpbui-chessgame-errorAt');
			if(widget._database.index >= widget._database.pgn.length) {
				errorAt.appendChild(document.createTextNode('Occurred at the end of the string.'));
			}
			else {
				errorAt.appendChild(document.createTextNode('Occurred at position ' + widget._database.index + ':'));
				errorAt.appendChild(buildTextElement('div', 'rpbui-chessgame-errorAtCode', ellipsisAt(widget._database.pgn, widget._database.index, 10, 40)));
			}
			result.appendChild(errorAt);
		}

		return result;
	}
	
	
	/**
	 * Refresh the widget.
	 *
	 * @param {rpbchess-ui.chessdb} widget
	 */
	function refresh(widget) {
		destroyContent(widget);
		if(widget._database === null) {
			return;
		}

		// Handle parsing error problems.
		if(widget._databaseIsError) {
			widget.element.append(buildErrorMessage(widget));
			return;
		}

		// Render the content.
		widget.element.append(buildSelector(widget));
		
		$('.rpbui-chessdb-selectMenu', widget.element).selectmenu({ appendTo: $('.rpbui-chessdb-selectMenuContainer', widget.element) });
	}
	
	
	/**
	 * Build the DOM node corresponding the selector.
	 * 
	 * @param {rpbchess-ui.chessdb} widget
	 */
	function buildSelector(widget) {
		var selectMenuClass = 'rpbui-chessdb-selectMenuContainer';
		if($.chessdb.selectMenuClass !== '') {
			selectMenuClass += ' ' + $.chessdb.selectMenuClass; 
		}
			
		var result = buildElement('div', selectMenuClass);
		var selector = buildElement('select', 'rpbui-chessdb-selectMenu');
		result.append(selector);
		
		var gameCount = widget._database.gameCount();
		for(var i=0; i<gameCount; ++i) {
			selector.append(buildSelectorItem(widget, i));
		}
		return result;
	}
	
	
	/**
	 * Build the DOM node corresponding to a game item in the selector.
	 * 
	 * @param {rpbchess-ui.chessdb} widget
	 * @param {number} itemIndex
	 */
	function buildSelectorItem(widget, itemIndex) {
		var game = widget._database.game(itemIndex);
		return buildTextElement('option', '', game.playerName('w') + ' - ' + game.playerName('b'));
	}


	/**
	 * Instantiate a new DOM element of the given type, that contains only text.
	 *
	 * @param {string} type
	 * @param {string} className Use `''` to not define a classname.
	 * @param {string} text
	 * @returns {Element}
	 */
	function buildTextElement(type, className, text) {
		var result = buildElement(type, className);
		result.appendChild(document.createTextNode(text));
		return result;
	}


	/**
	 * Instantiate a new DOM element of the given type and set its classname.
	 *
	 * @param {string} type
	 * @param {string} className Use `''` to not define a classname.
	 * @returns {Element}
	 */
	function buildElement(type, className) {
		var result = document.createElement(type);
		if(className !== '') {
			result.className = className;
		}
		return result;
	}



	// ---------------------------------------------------------------------------
	// Widget registration in the jQuery widget framework.
	// ---------------------------------------------------------------------------

	/**
	 * Register a 'chessdb' widget in the jQuery widget framework.
	 */
	$.widget('rpbchess-ui.chessdb',
	{
		/**
		 * Default options.
		 */
		options:
		{
			/**
			 * String describing the game (PGN format).
			 */
			pgn: '*',

			/**
			 * URL from which the PGN data should be retrieved. If provided, the `pgn` attribute becomes read-only.
			 */
			url: ''
		},


		/**
		 * Hold the parsed information about the chess games.
		 * @type {kokopu.Database}
		 */
		_database: null,
		
		
		/**
		 * Whether an error has occurred while loading the database.
		 */
		_databaseIsError: false,


		/**
		 * Constructor.
		 */
		_create: function() {
			this.element.addClass('rpbui-chessdb');

			this.options.url = initializeURL(this, this.options.url);
			if(!this.options.url) {
				this.options.pgn = initializePGN(this, this.options.pgn);
			}

			refresh(this);
		},


		/**
		 * Destructor.
		 */
		_destroy: function() {
			destroyContent(this);
			this.element.removeClass('rpbui-chessgame');
		},


		/**
		 * Option setter.
		 */
		_setOption: function(key, value) {
			switch(key) {

				case 'url': value = initializeURL(this, value); break;

				case 'pgn':
					if(this.options.url) { return; }
					value = initializePGN(this, value);
					break;
			}

			this.options[key] = value;
			refresh(this);
		}


	}); /* $.widget('rpbchess-ui.chessdb', { ... }) */

})(/* global kokopu */ kokopu, /* global jQuery */ jQuery);
