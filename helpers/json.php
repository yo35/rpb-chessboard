<?php

/**
 * JSON-related functions.
 *
 * @author Yoann Le Montagner
 */
abstract class RPBChessboardHelperJSON
{
	/**
	 * Return a string representing a set of aspect options applicable to
	 * chessboard widgets, in a JSON format.
	 *
	 * @param int $squareSize
	 * @param boolean $showCoordinates
	 * @return string This string can be inlined in a javascript script.
	 */
	public static function formatChessWidgetAttributes($squareSize, $showCoordinates)
	{
		$retVal = array();
		if(!is_null($squareSize     )) $retVal[] = 'squareSize: '      . json_encode($squareSize     );
		if(!is_null($showCoordinates)) $retVal[] = 'showCoordinates: ' . json_encode($showCoordinates);
		return '{' . implode(', ', $retVal) . '}';
	}
}
