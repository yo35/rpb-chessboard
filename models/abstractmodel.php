<?php

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
 *
 * @author Yoann Le Montagner
 */
abstract class RPBChessboardAbstractModel
{
	private $name = null;
	private $methodIndex = array();


	/**
	 * Constructor
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
		if(is_null($trait)) {
			$modelName = $this->getName();
			throw new Exception("Invalid call to method `$method` in the model `$modelName`.");
		}
		return $trait->$method($args);
	}


	/**
	 * Import a trait to the current class.
	 */
	public function loadTrait($traitName)
	{
		// Load the definition of the trait, and instantiate it.
		$fileName  = strtolower($traitName);
		$className = 'RPBChessboardTrait' . $traitName;
		require_once(RPBCHESSBOARD_ABSPATH.'models/traits/'.$fileName.'.php');
		$trait = new $className();

		// List all the public methods of the trait, and register them
		// to the method index of the current model.
		foreach(get_class_methods($trait) as $method) {
			$this->methodIndex[$method] = $trait;
		}
	}


	/**
	 * Return the name of the model.
	 */
	public function getName()
	{
		if(is_null($this->name)) {
			if(preg_match('/^RPBChessboardModel(.*)$/', get_class($this), $matches)) {
				$this->name = $matches[1];
			}
			else {
				$this->name = '';
			}
		}
		return $this->name;
	}
}
