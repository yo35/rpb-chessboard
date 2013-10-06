<?php

/**
 * Base class for controllers used by the RPBChessboard plugin.
 *
 * @author Yoann Le Montagner
 */
abstract class RPBChessboardAbstractController
{
	private $name = null;


	/**
	 * Entry point of the controller.
	 *
	 * This method is called from the top-level controller (which is part of the
	 * internal machinery of the Wordpress framework).
	 *
	 * The default action implemented by this method is to search a view and a model
	 * with the same name that the controller, to load them, and to execute
	 * the display method of the view.
	 */
	public function run()
	{
		$shortname = $this->getName();
		$filename  = strtolower($shortname);
		$modelName = 'RPBChessboardModel' . $shortname;
		$viewName  = 'RPBChessboardView'  . $shortname;
		require_once(RPBCHESSBOARD_ABSPATH.'models/admin/'.$filename.'.php');
		require_once(RPBCHESSBOARD_ABSPATH.'views/admin/' .$filename.'.php');
		$model = new $modelName();
		$view  = new $viewName($model);
		$view->display();
	}


	/**
	 * Return the name of the controller.
	 */
	public function getName()
	{
		if(is_null($this->name)) {
			if(preg_match('/^RPBChessboardController(.*)$/', get_class($this), $matches)) {
				$this->name = $matches[1];
			}
			else {
				$this->name = '';
			}
		}
		return $this->name;
	}
}
