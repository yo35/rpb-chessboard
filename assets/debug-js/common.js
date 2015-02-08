/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a WordPress plugin.                *
 *    Copyright (C) 2013-2015  Yoann Le Montagner <yo35 -at- melix.net>       *
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


/* jshint unused:false */
/* jshint globalstrict:true */
'use strict';


/**
 * Number of tests performed.
 */
var testCounter = 0;


/**
 * Number of tests that succeed.
 */
var successCounter = 0;


/**
 * Hold the registered exceptions.
 */
var registeredExceptions = [];


/**
 * Hold the registered tests.
 */
var registeredTests = [];


/**
 * Register a new type of exception.
 *
 * @param {function} constructor
 * @param {function} formatter
 */
function registerException(constructor, formatter) {
	registeredExceptions.push({ constructor:constructor, formatter:formatter });
}


/**
 * Register a new unit test.
 *
 * @param {string} id
 * @param {function} unitTest
 */
function registerTest(id, unitTest) {
	registeredTests.push({ id:id, unitTest:unitTest });
}


/**
 * Refresh the global counters.
 */
function refreshCounters() {

	// Total number of tests
	var div1 = document.getElementById('testCounter');
	div1.innerHTML = '';
	div1.appendChild(document.createTextNode(testCounter + ' tests performed.'));

	// Number of successes/failures
	var div2 = document.getElementById('successCounter');
	div2.innerHTML = '';
	if(testCounter===successCounter) {
		div2.appendChild(document.createTextNode('All tests were successful.'));
		div2.className = 'allSuccessful';
	}
	else {
		div2.appendChild(document.createTextNode((testCounter-successCounter) + ' tests failed.'));
		div2.className = 'someFailure';
	}
}


/**
 * Base report method.
 *
 * @param {boolean} success
 * @param {string} message
 */
function printMessage(success, message) {
	var div = document.createElement('div');
	div.className = 'message ' + (success ? 'success' : 'failure');
	div.appendChild(document.createTextNode(message));
	document.getElementById('report').appendChild(div);
}


/**
 * Report that a test succeeded.
 *
 * @param {string} label
 */
function printSuccess(label) {
	printMessage(true, label);
}


/**
 * Print an error message to report that a function does not return what was expected.
 *
 * @param {string} label
 * @param {mixed} observedValue
 * @param {mixed} expectedValue
 */
function printBadValue(label, observedValue, expectedValue) {
	var message = label + ' => observed >>' + observedValue + '<<';
	if(typeof expectedValue === 'undefined') {
		message += ' while error expected';
	}
	else {
		message += ' while expected >>' + expectedValue + '<<';
	}
	printMessage(false, message);
}


/**
 * Print an error message to report that an exception was thrown during a test.
 *
 * @param {string} label
 * @param {mixed} exception
 */
function printException(label, exception) {
	var message = label + ' => ';
	var isRegisteredException = false;

	// Try to match the exception with the registered types
	for(var i=0; i<registeredExceptions.length; ++i) {
		var re = registeredExceptions[i];
		if(exception instanceof re.constructor) {
			message += re.formatter(exception);
			isRegisteredException = true;
			break;
		}
	}

	// Fallback case if the exception do not match the registered types
	if(!isRegisteredException) {
		message += 'unknown exception => ' + exception;
	}

	// Reporting
	printMessage(false, message);
}


/**
 * Check the returned value of a function.
 *
 * @param {string} label
 * @param {function} fun
 * @param {mixed} expectedValue
 */
function test(label, fun, expectedValue) {
	try {
		++testCounter;
		var observedValue = fun();
		if(observedValue === expectedValue) {
			++successCounter;
			printSuccess(label);
		}
		else {
			printBadValue(label, observedValue, expectedValue);
		}
	}
	catch(exception) {
		printException(label, exception);
	}
}


/**
 * Check the exception thrown by a function.
 *
 * @param {string} label
 * @param {function} fun
 * @param {function} checkException
 */
function testError(label, fun, checkException) {
	try {
		++testCounter;
		var observedValue = fun();
		printBadValue(label, observedValue);
	}
	catch(exception) {
		if(checkException(exception)) {
			++successCounter;
			printSuccess(label);
		}
		else {
			printException(label, exception);
		}
	}

}


/**
 * Play the unit tests.
 *
 * @param {string} [pattern='*']
 */
function playTests(pattern) {
	if(typeof pattern === 'undefined') {
		pattern = '*';
	}

	// Match the test ID against the test selection pattern.
	pattern = pattern.split('.');
	function match(id) {
		id = id.split('.');
		for(var i=0; i<pattern.length; ++i) {
			if(pattern[i] === '*') {
				return true;
			}
			else if(i>=id.length || pattern[i]!==id[i]) {
				return false;
			}
		}
		return pattern.length===id.length;
	}

	// Return the callback to use to play the given test.
	function playTest(unitTest) {
		return function() {
			unitTest();
			refreshCounters();
		};
	}

	// Queue all the tests.
	for(var i=0; i<registeredTests.length; ++i) {
		if(match(registeredTests[i].id)) {
			setTimeout(playTest(registeredTests[i].unitTest), 0);
		}
	}
	setTimeout(refreshCounters, 0);
}
