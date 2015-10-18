<?php
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


/**
 * Base class for the models used by the RPBChessboard plugin.
 *
 * In the RPBChessboard plugin, the models are 'trait'-oriented, meaning that
 * most of their methods defined in separated trait classes, that may be shared
 * between models, and that are dynamically loaded when the model instances are
 * created.
 *
 * To ensure compatibility with PHP versions older than 5.4 (in which traits
 * are implemented natively), the trait mechanism is emulated based on a "magic"
 * method `__call()` in this base model class.
 */
abstract class RPBChessboardAbstractModel {

	private $modelName;
	private $methodIndex = array();
	private $selfDelegatableMethods = array();


	/**
	 * Constructor.
	 */
	public function __construct() {}


	/**
	 * Magic method called when trying to invoke inaccessible (or inexisting) methods.
	 * In this case, the call is deferred to the delegate model that exposes a method
	 * with the corresponding name.
	 */
	public function __call($method, $args) {

		// Ensure that the requested method exists in one of the delegate models.
		if(!isset($this->methodIndex[$method])) {
			$modelName = $this->getModelName();
			throw new Exception("Invalid call to method `$method` in the model `$modelName`.");
		}

		// Call the method on the delegate model.
		$delegateModel = $this->methodIndex[$method];
		return call_user_func_array(array($delegateModel, $method), $args);
	}


	/**
	 * Load a delegate model.
	 *
	 * @param string $modelName Name of the model.
	 * @param mixed ... Arguments to pass to the model (optional).
	 */
	protected function loadDelegateModel($modelName) {

		// Load the model.
		$args = func_get_args();
		$delegateModel = call_user_func_array(array('RPBChessboardHelperLoader', 'loadModel'), $args);

		// Register the new delegatable methods.
		$this->methodIndex += $delegateModel->getDelegatableMethods();
	}


	/**
	 * Register a delegatable method of the current model.
	 */
	protected function registerDelegatableMethod() {
		$methods = func_get_args();
		$this->selfDelegatableMethods += $methods;
	}


	/**
	 * Return the list of methods that can be called on the current model through the delegate mechanism.
	 *
	 * @return array
	 */
	private function getDelegatableMethods() {
		$result = $this->methodIndex;
		foreach($this->selfDelegatableMethods as $method) {
			$result[$method] = $this;
		}
		return $result;
	}


	/**
	 * Return the name of the model.
	 *
	 * @return string
	 */
	public function getModelName() {
		if(!isset($this->modelName)) {
			$this->modelName = preg_match('/^RPBChessboardModel(.*)$/', get_class($this), $m) ? $m[1] : '';
		}
		return $this->modelName;
	}
}
