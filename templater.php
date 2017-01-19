<?php

Dyli::initialize();

/**
 * Matches all {[href:key]} in template and replaces with URLs.
 *
 * @param  string $page
 * @return string
 */
function place_hrefs($page) {
	$json = file_get_contents(__DIR__ . '\..\dynamic-links.json');
	$json = json_decode($json, true);

	foreach ($json as $key => $data) {

		$href = Dyli::stableHref($key);
		$page = preg_replace("@{\[href:$key\]}@", $href, $page);
	}

	return $page;
}

\Sleepy\Hook::doAction('render_template',  'place_hrefs');
