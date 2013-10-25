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
	private $contentFiltered = false;


	/**
	 * Constructor.
	 *
	 * @param array $atts Attributes passed with the short-code.
	 * @param string $content Short-code enclosed content.
	 */
	public function __construct($atts, $content)
	{
		parent::__construct();
		$this->atts    = is_array($atts) ? $atts : array();
		$this->content = $content;
	}


	/**
	 * Return the name of the view to use.
	 *
	 * @return string
	 */
	public abstract function getViewName();


	/**
	 * Return the attributes passed with the short-code.
	 *
	 * @return array
	 */
	public function getAttributes()
	{
		return $this->atts;
	}


	/**
	 * Return the enclosed short-code content.
	 *
	 * @return string
	 */
	public function getContent()
	{
		if(!$this->contentFiltered) {
			$this->content = $this->filterShortcodeContent($this->content);
			$this->contentFiltered = true;
		}
		return $this->content;
	}


	/**
	 * Pre-process the short-code enclosed content, for instance to get rid of the
	 * auto-format HTML tags introduced by the Wordpress engine. By default, this
	 * function returns the raw content "as-is". The function should be re-implemented
	 * in the derived models.
	 *
	 * @param string $content Raw content.
	 * @return string Filtered content.
	 */
	protected function filterShortcodeContent($content)
	{
		return $content;
	}
}
