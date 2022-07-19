<?php

use Mlk9\Setting\Setting;
use PHPUnit\Framework\TestCase;


class SettingTest extends TestCase
{
	
	public function testCanSetData()
	{
		$this->assertEquals(Setting::class, Setting::set(['KEY1' => 'VALUE1']));
	}

	
}