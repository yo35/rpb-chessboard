<?php
/******************************************************************************
 *                                                                            *
 *    This file is part of RPB Chessboard, a Wordpress plugin.                *
 *    Copyright (C) 2013-2014  Yoann Le Montagner <yo35 -at- melix.net>       *
 *                                                                            *
 *    This program is free software: you can redistribute it and/or modify    *
 *    it under the terms of the GNU General Public License as published by    *
 *    the Free Software Foundation, either version 3 of the License, or       *
 *    (at your option) any later version.                                     *
 *                                                                            *
 *    This program is distributed in the hope that it will be useful,         *
 *    but WITHOUT ANY WARRANTY; without even the implied warranty of          *
 *    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the           *
 *    GNU General Public License for more details.                            *
 *                                                                            *
 *    You should have received a copy of the GNU General Public License       *
 *    along with this program.  If not, see <http://www.gnu.org/licenses/>.   *
 *                                                                            *
 ******************************************************************************/


require_once(RPBCHESSBOARD_ABSPATH.'models/abstract/abstractmodel.php');


/**
 * Base class for the models used in the frontend of the RPBChessboard plugin.
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
