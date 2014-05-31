<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a Wordpress plugin.                *
 *    Copyright (C) 2013-2014  Yoann Le Montagner <yo35 -at- melix.net>       *
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


require_once(RPBCHESSBOARD_ABSPATH . 'helpers/loader.php');


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
abstract class RPBChessboardAbstractModel
{
	private $name;
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
		$trait = $this->methodIndex[$method];
		if(!isset($trait)) {
			$modelName = $this->getName();
			throw new Exception("Invalid call to method `$method` in the model `$modelName`.");
		}
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
		$trait = call_user_func_array(array('RPBChessboardHelperLoader', 'loadTrait'), func_get_args());

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
	public function getName()
	{
		if(!isset($this->name)) {
			$this->name = preg_match('/^RPBChessboardModel(.*)$/', get_class($this), $matches) ? $matches[1] : '';
		}
		return $this->name;
	}


	/**
	 * Return the name of the view to use. By default, this is the model name.
	 *
	 * @return string
	 */
	public function getViewName()
	{
		return $this->getName();
	}


	/**
	 * Return the name of the template to use. By default, this is the model name.
	 *
	 * @return string
	 */
	public function getTemplateName()
	{
		return $this->getName();
	}
}
