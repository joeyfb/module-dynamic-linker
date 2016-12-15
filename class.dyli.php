<?php

class Dyli {
	/**
	 * json array of links
	 * @private
	 */
	private static $json;

	private static $_instance;

	public static $is_initialized = false;

	private function __clone() {}

	/**
	 * Constructor
	 */
	private function __construct() {
		$json = file_get_contents('links.json', FILE_USE_INCLUDE_PATH);

		self::$json = json_decode($json, true);
	}

	/**
	 * Initialized the Dyli class
	 */
	public static function initialize() {
		if (!self::$is_initialized) {
			self::$is_initialized = true;
			self::$_instance = new Dyli;
		}
	}

	/**
	 * Checks if $name and ENV valid
	 */
	public static function valid($name) {
		$json = self::$json;

		// print_r(array(array_key_exists($name, $json), array_key_exists(ENV, $json[$name]['ENV']), ENV));

		if (array_key_exists($name, $json)
			&& array_key_exists("ENV", $json[$name])
			&& array_key_exists(ENV, $json[$name]['ENV'])) {

			return true;

		} else if (array_key_exists($name, $json)
			&& array_key_exists("href", $json[$name])) {

			return true;

		} else {

			return false;
		}
	}

	/**
	 * Returns formatted link
	 */
	public static function get($name, $path, $text, $class) {
		$json = self::$json;

		if (!self::valid($name)) {
			return "<a href=\"/404\" class=\"$class\">$text</a>";
		} else {
			$link = $json[$name];
		}

		$href = self::href($name, $path);

		$attrs = '';
		foreach ($link['ATTR'] as $key => $value) {

			if ($key == 'class') {
				$value .= " $class";
			}

			$attrs .= "$key=\"$value\" ";
		}
		return "<a href=\"$href\" $attrs>$text</a>";
	}

	/**
	 * Gets HREF link, if mobile device and mobile href exists serves that.
	 */
	public static function href($name, $path="") {
		if (!self::valid($name)) {
			return "/404";
		} else {
			$link = self::$json[$name];
		}

		$m = new Module\MobiDetect\Detector();
		if (isset($link["mobile-href"]) && $m->isMobile()) {
			$href = $link["mobile-href"];

		} else if (isset($link["href"])) {
			$href = $link["href"];

		} else {
			$href = $link['ENV'][ENV];

		}

		return $href . $path;
	}

	/**
	 * Returns id link if exist, or href if not
	 */
	public static function stableHref ($name, $path = '') {
		$json = self::$json;

		if (!self::valid($name)) {
			return "/404";
		} else {
			$link = $json[$name];
		}

		if ( ! isset($json["redirect"]) || $link["direct"] ) {
			return self::href($name, $path);
		} else {
			return $json["redirect"] . "/?id=" . $name;
		}
	}
}
