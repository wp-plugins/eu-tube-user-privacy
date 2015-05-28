<?php
class EMAYoutubeNocookie {
	static function changeDomain($content) {
		$pattern = '/youtube\.com\/(v|embed)\//s';
		$replace = 'youtube-nocookie.com/$1/';
		$content = preg_replace($pattern, $replace, $content);

		return $content;
	}
}
