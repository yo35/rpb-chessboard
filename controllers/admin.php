<?php

/**
 * Controller for the backend.
 *
 * @author Yoann Le Montagner
 */
class RPBChessboardControllerAdmin
{
	private $modelName;
	private $model = null;

	/**
	 * Constructor
	 *
	 * @param string $modelName Name of the model to use.
	 */
	public function __construct($modelName)
	{
		$this->modelName = $modelName;
	}

	/**
	 * Load (if necessary) and return the model. The model name provided in the constructor
	 * is supposed to derive from the class RPBChessboardAbstractAdminModel.
	 */
	public function getModel()
	{
		if(is_null($this->model)) {
			$modelName = $this->modelName;
			$fileName  = strtolower($modelName);
			$className = 'RPBChessboardModel' . $modelName;
			require_once(RPBCHESSBOARD_ABSPATH.'models/'.$fileName.'.php');
			$this->model = new $className();
		}
		return $this->model;
	}

	/**
	 * Entry-point of the controller.
	 */
	public function run()
	{
		// Load the model
		$model = $this->getModel();

		// Create the view
		require_once(RPBCHESSBOARD_ABSPATH.'views/admin.php');
		$view = new RPBChessboardViewAdmin($model);

		// Display the view
		$view->display();
	}
}
