<?php

/**
 * Base class for the views used by the RPBChessboard plugin.
 *
 * @author Yoann Le Montagner
 */
abstract class RPBChessboardAbstractView
{
	private $model;


	/**
	 * Constructor
	 */
	public function __construct($model)
	{
		$this->model = $model;
	}


	/**
	 * Method called the controller to display the view.
	 */
	public abstract function display();


	/**
	 * Model associated to the view.
	 */
	public function getModel()
	{
		return $this->model;
	}
}
