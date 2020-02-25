<?php

namespace mnc;


class HTMLHelper {

	/**
	 * creates an A Tag (Link)
	 * @param string $url string of href
	 * @param string $text string of a tag
	 * @param string $class
	 * @param string $title
	 *
	 * @return string the generates HTML Link
	 */
	public static function atag($url, $text, $class = '', $title = '') {
		$element = '<a href="%s"'.self::format_class($class).'>%s</a>';
		return sprintf($element,$url, $text);
	}

	public static function div($content, $class = '') {
		$element = '<div '.self::format_class($class).'>%s</div>';
		return sprintf($element,$content);
	}

	public static function renderClassArray(array $arrClass) {
		return self::format_class(implode(", ", $arrClass));
	}

	public static function format_class($class) {
		if($class = '') {
			return '';
		}
		return ' class="'.$class.'" ';
	}

	public static function format_id($dom_id) {
		if($dom_id = '') {
			return '';
		}
		return ' id="' . $dom_id . '" ';
	}

}