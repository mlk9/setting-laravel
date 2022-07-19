<?php

use Mlk9\Setting\Facades\Setting;
use PHPUnit\Framework\TestCase;


class SettingTest extends TestCase
{
	
	public function testCanSetData()
	{
		$this->assertEquals(Setting::class, Setting::set(['key1' => 'dwdwd']));
	}

	
}