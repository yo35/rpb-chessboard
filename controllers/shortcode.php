<?php

require_once(RPBCHESSBOARD_ABSPATH.'controllers/abstractcontroller.php');


/**
 * Controller for the frontend.
 *
 * @author Yoann Le Montagner
 */
class RPBChessboardControllerShortcode extends RPBChessboardAbstractController
{
	private $atts   ;
	private $content;


	/**
	 * Constructor
	 *
	 * @param string $modelName Name of the model to use. It is supposed to refer
	 *        to a model that inherits from the class RPBChessboardAbstractShortcodeModel.
	 * @param array $atts Attributes passed with the short-code.
	 * @param string $content Enclosed short-code content.
	 */
	public function __construct($modelName, $atts, $content)
	{
		parent::__construct($modelName);
		$this->atts    = $atts   ;
		$this->content = $content;
	}


	/**
	 * Instantiate a model object.
	 */
	protected function loadModel($className)
	{
		return new $className($this->atts, $this->content);
	}


	/**
	 * Entry-point of the controller.
	 */
	public function run()
	{
		// Load the model
		$model = $this->getModel();

		// Create the view
		$viewName  = $model->getViewName();
		$fileName  = strtolower($viewName);
		$className = 'RPBChessboardView' . $viewName;
		require_once(RPBCHESSBOARD_ABSPATH.'views/'.$fileName.'.php');
		$view = new $className($model);

		// Display the view
		ob_start();
		$view->display();
		return ob_get_clean();
	}
}
