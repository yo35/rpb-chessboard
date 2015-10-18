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


	/**
	 * Constructor.
	 */
	public function __construct() {}


	/**
	 * Magic method called when trying to invoke inaccessible (or inexisting) methods.
	 * In this case, the call is deferred to the imported trait that exposes a method
	 * with the corresponding name.
	 */
	public function __call($method, $args)
	{
		if(!isset($this->methodIndex[$method])) {
			$modelName = $this->getModelName();
			throw new Exception("Invalid call to method `$method` in the model `$modelName`.");
		}
		$trait = $this->methodIndex[$method];
		return call_user_func_array(array($trait, $method), $args);
	}


	/**
	 * Import a trait to the current class.
	 *
	 * @param string $traitName Name of the trait.
	 * @param mixed ... Arguments to pass to the trait (optional).
	 */
	public function loadTrait($traitName)
	{
		// Load the definition of the trait, and instantiate it.
		$args  = func_get_args();
		$trait = call_user_func_array(array('RPBChessboardHelperLoader', 'loadTrait'), $args);

		// List all the public methods of the trait, and register them
		// to the method index of the current model.
		foreach(get_class_methods($trait) as $method) {
			$this->methodIndex[$method] = $trait;
		}
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
