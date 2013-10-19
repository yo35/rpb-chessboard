<?php

require_once(RPBCHESSBOARD_ABSPATH.'models/abstracttoplevelshortcodemodel.php');


/**
 * Model associated to the [pgn][/pgn] short-code page in the frontend.
 *
 * @author Yoann Le Montagner
 */
class RPBChessboardModelPgn extends RPBChessboardAbstractTopLevelShortcodeModel
{
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
	 * By default, the wordpress engine turn the line breaks into the corresponding
	 * HTML tag (<br/>), or into paragraph separator tags (<p></p>).
	 * This filter cancel this operation.
	 */
	protected function filterShortcodeContent($content)
	{
		// Replace the </p><p> and <br/> with line breaks.
		$content = preg_replace('/ *<\/p>\s*<p> */', "\n\n", $content);
		$content = preg_replace('/<br *\/>\n/', "\n", $content);

		// Trim the content.
		$content = trim($content);

		// Return the result
		return $content;
	}
}
