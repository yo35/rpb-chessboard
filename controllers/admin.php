<?php

require_once(RPBCHESSBOARD_ABSPATH.'controllers/abstractcontroller.php');


/**
 * Controller for the backend.
 *
 * @author Yoann Le Montagner
 */
class RPBChessboardControllerAdmin extends RPBChessboardAbstractController
{
	/**
	 * Constructor
	 *
	 * @param string $modelName Name of the model to use. It is supposed to refer
	 *        to a model that inherits from the class RPBChessboardAbstractAdminModel.
	 */
	public function __construct($modelName)
	{
		parent::__construct($modelName);
	}


	/**
	 * Entry-point of the controller.
	 */
	public function run()
	{
		// Load the model
		$model = $this->getModel();

		// Process the post-action, if any.
		$action = $model->getPostAction();
		if(!is_null($action)) {
			$model->loadTrait('Action' . $action);
			$model->processRequest();
		}

		// Create the view
		require_once(RPBCHESSBOARD_ABSPATH.'views/admin.php');
		$view = new RPBChessboardViewAdmin($model);

		// Display the view
		$view->display();
	}
}
