<?php

/**
 * Base class for the controllers.
 *
 * @author Yoann Le Montagner
 */
abstract class RPBChessboardAbstractController
{
	private $modelName;
	private $model = null;


	/**
	 * Constructor
	 *
	 * @param string $modelName Name of the model to use.
	 */
	protected function __construct($modelName)
	{
		$this->modelName = $modelName;
	}


	/**
	 * Load (if necessary) and return the model.
	 */
	public function getModel()
	{
		if(is_null($this->model)) {
			$modelName = $this->modelName;
			$fileName  = strtolower($modelName);
			$className = 'RPBChessboardModel' . $modelName;
			require_once(RPBCHESSBOARD_ABSPATH.'models/'.$fileName.'.php');
			$this->model = $this->loadModel($className);
		}
		return $this->model;
	}


	/**
	 * Instanciate a model object.
	 *
	 * @param string $className Class name of the model to instanciate.
	 * @return RPBChessboardAbstractModel
	 */
	protected function loadModel($className)
	{
		return new $className();
	}


	/**
	 * Entry-point of the controller.
	 */
	public abstract function run();
}
