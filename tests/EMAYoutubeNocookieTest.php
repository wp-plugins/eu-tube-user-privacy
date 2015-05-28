<?php
class EMAYoutubeNocookieTest extends PHPUnit_Framework_TestCase {
	public function testTopLevel() {
		$content = 'https://www.youtube.com/';
		$expected = 'https://www.youtube.com/';
		$process = EMAYoutubeNocookie::changeDomain($content);
		
		$this->assertEquals($expected, $process);
	}

	public function testWatch() {
		$content = 'https://www.youtube.com/watch?v=CEpu2keRMwI';
		$expected = 'https://www.youtube.com/watch?v=CEpu2keRMwI';
		$process = EMAYoutubeNocookie::changeDomain($content);
		
		$this->assertEquals($expected, $process);
	}

	public function testEmbed() {
		$content = 'https://www.youtube.com/embed/CEpu2keRMwI https://www.youtube.com/v/CEpu2keRMwI';
		$expected = 'https://www.youtube-nocookie.com/embed/CEpu2keRMwI https://www.youtube-nocookie.com/v/CEpu2keRMwI';
		$process = EMAYoutubeNocookie::changeDomain($content);
		
		$this->assertEquals($expected, $process);
	}

}
