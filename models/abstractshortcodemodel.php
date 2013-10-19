<?php

require_once(RPBCHESSBOARD_ABSPATH.'models/abstractmodel.php');


/**
 * Base class for the models used in the frontend of the RPBChessboard plugin.
 *
 * @author Yoann Le Montagner
 */
abstract class RPBChessboardAbstractShortcodeModel extends RPBChessboardAbstractModel
{
	private $atts   ;
	private $content;


	/**
	 * Constructor
	 *
	 * @param array $atts Attributes passed with the short-code.
	 * @param string $content Enclosed short-code content.
	 */
	public function __construct($atts, $content)
	{
		parent::__construct();
		$this->atts    = $atts==='' ? array() : $atts;
		$this->content = $content;
	}
}
