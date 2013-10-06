<?php

/**
 * Base class for the views used by the RPBChessboard plugin.
 *
 * @author Yoann Le Montagner
 */
abstract class RPBChessboardAbstractView
{
	private $name = null;
	private $model;


	/**
	 * Constructor
	 */
	public function __construct($model)
	{
		$this->model = $model;
	}


	/**
	 * Print the model.
	 */
	public function display()
	{
		$model = $this->getModel();
		echo '<div class="wrap">';
		include(RPBCHESSBOARD_ABSPATH.'templates/admin/header.php');
		include(RPBCHESSBOARD_ABSPATH.'templates/admin/'.strtolower($model->getTemplateName()).'.php');
		echo '</div>';
	}


	/**
	 * Model associated to the view.
	 */
	public function getModel()
	{
		return $this->model;
	}


	/**
	 * Return the name of the view.
	 */
	public function getName()
	{
		if(is_null($this->name)) {
			if(preg_match('/^RPBChessboardView(.*)$/', get_class($this), $matches)) {
				$this->name = $matches[1];
			}
			else {
				$this->name = '';
			}
		}
		return $this->name;
	}
}
