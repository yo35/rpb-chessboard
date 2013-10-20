<?php

require_once(RPBCHESSBOARD_ABSPATH.'models/abstractshortcodemodel.php');
require_once(RPBCHESSBOARD_ABSPATH.'helpers/json.php');


/**
 * Model associated to the [pgndiagram] short-code page in the frontend.
 *
 * @author Yoann Le Montagner
 */
class RPBChessboardModelPgnDiagram extends RPBChessboardAbstractShortcodeModel
{
	private $chessWidgetAttributes = null;


	/**
	 * Constructor.
	 *
	 * @param array $atts Attributes passed with the short-code.
	 * @param string $content Short-code enclosed content.
	 */
	public function __construct($atts, $content)
	{
		parent::__construct($atts, $content);
		$this->loadTrait('ChessWidgetCustom', $this->getAttributes());
	}


	/**
	 * Return the name of the view to use.
	 *
	 * @return string
	 */
	public function getViewName()
	{
		return 'PgnDiagram';
	}


	/**
	 * Return the list of aspect options applicable to chess widgets, in a JSON-format.
	 */
	public function getChessWidgetAttributes()
	{
		if(is_null($this->chessWidgetAttributes))
		{
			// Concatenate all the attributes in a JSON format.
			$str = RPBChessboardHelperJSON::formatChessWidgetAttributes(
				$this->getCustomSquareSize     (),
				$this->getCustomShowCoordinates()
			);

			// Trim the brace characters to fulfill the PGN comment syntax.
			// TODO: a true escaping mechanism in PGN comments would be cleaner.
			$this->chessWidgetAttributes = preg_replace('/^{|}$/', '', $str);
		}
		return $this->chessWidgetAttributes;
	}
}
