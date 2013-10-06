<?php

/**
 * Base class for the models used by the RPBChessboard plugin.
 *
 * @author Yoann Le Montagner
 */
abstract class RPBChessboardAbstractModel
{
	private $name = null;


	/**
	 * Return the title of the current page.
	 */
	public abstract function getTitle();


	/**
	 * Return the name of the template to use. By default, this is the template with
	 * the same name that the model.
	 */
	public function getTemplateName()
	{
		return $this->getName();
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
