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


require_once(RPBCHESSBOARD_ABSPATH . 'models/abstract/abstractmodel.php');


/**
 * Base class for the models used to render the plugin shortcodes.
 */
abstract class RPBChessboardAbstractModelShortcode extends RPBChessboardAbstractModel
{
	private $shortcodeName;
	private $atts;
	private $content;
	private $contentFiltered = false;
	private $uniqueID;


	/**
	 * Constructor.
	 *
	 * @param array $atts Attributes passed with the shortcode.
	 * @param string $content Shortcode enclosed content.
	 */
	public function __construct($atts, $content)
	{
		parent::__construct();
		$this->atts    = (isset($atts   ) && is_array ($atts   )) ? $atts    : array();
		$this->content = (isset($content) && is_string($content)) ? $content : '';
	}


	/**
	 * Use the "Shortcode" view by default.
	 *
	 * @return string
	 */
	public function getViewName()
	{
		return 'Shortcode';
	}


	/**
	 * The name of the template to use is the name of the shortcode.
	 */
	public function getTemplateName()
	{
		return $this->getShortcodeName();
	}


	/**
	 * Name of the shortcode.
	 *
	 * @return string
	 */
	public function getShortcodeName()
	{
		if(!isset($this->shortcodeName)) {
			$this->shortcodeName = preg_match('/^Shortcode(.*)$/', $this->getName(), $m) ? $m[1] : '';
		}
		return $this->shortcodeName;
	}


	/**
	 * Return the attributes passed with the shortcode.
	 *
	 * @return array
	 */
	public function getAttributes()
	{
		return $this->atts;
	}


	/**
	 * Return the enclosed shortcode content.
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
	 * Pre-process the shortcode enclosed content, for instance to get rid of the
	 * auto-format HTML tags introduced by the WordPress engine. By default, this
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


	/**
	 * Return a string that may be used as a unique DOM node ID.
	 *
	 * @return string
	 */
	public function getUniqueID()
	{
		if(!isset($this->uniqueID)) {
			$this->uniqueID = self::makeUniqueID();
		}
		return $this->uniqueID;
	}


	/**
	 * Allocate a new HTML node ID.
	 *
	 * @return string
	 */
	private static function makeUniqueID()
	{
		if(!isset(self::$idPrefix)) {
			self::$idPrefix = 'rpbchessboard-' . uniqid() . '-';
		}
		++self::$idCounter;
		return self::$idPrefix . self::$idCounter;
	}


	/**
	 * Global ID counter.
	 */
	private static $idCounter = 0;


	/**
	 * Prefix for the dynamically allocated DOM IDs.
	 */
	private static $idPrefix;
}
