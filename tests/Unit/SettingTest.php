<?php

namespace Mlk9\SettingPackage\Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Mlk9\Setting\Facades\Setting;
use Mlk9\SettingPackage\Tests\TestCase;

class SettingTest extends TestCase
{
	use RefreshDatabase;
	
	public function testCanSetData()
	{
		Setting::set(['key1' => 'value1','key2' => 'value2','key3' => 'value3']);
		$this->assertEquals(Setting::get('key1'),'value1');
		$this->assertEquals(Setting::get('key2'),'value2');
		$this->assertEquals(Setting::get('key3'),'value3');
		$this->assertEquals(Setting::all(),['key1' => 'value1','key2' => 'value2','key3' => 'value3']);
	}

	public function testCanSetTrueOrFalseData()
	{
		Setting::set(['key1' => true,'key2' => false]);
		$this->assertEquals(Setting::get('key1'),true);
		$this->assertEquals(Setting::get('key2'),false);
	}

	public function testDestroyData()
	{
		Setting::set(['key1' => 'value1','key2' => 'value2','key3' => 'value3']);
		Setting::destroy('key1');
		$this->assertEquals(Setting::all(),['key2' => 'value2','key3' => 'value3']);
		Setting::destroyAll();
		$this->assertEquals(Setting::all(),[]);
	}

	public function testRefreshData()
	{
		Setting::set(['key1' => 'value1','key2' => 'value2','key3' => 'value3']);
		Setting::refreshSalts();
		$this->assertEquals(Setting::get('key1'),'value1');
		$this->assertEquals(Setting::get('key2'),'value2');
		$this->assertEquals(Setting::get('key3'),'value3');
		$this->assertEquals(Setting::all(),['key1' => 'value1','key2' => 'value2','key3' => 'value3']);
	}

	public function testReplaceData()
	{
		Setting::set(['key1' => 'value1','key2' => 'value2','key3' => 'value3']);
		Setting::replaceAllConfigs();
		$this->assertEquals(config('key1',null),'value1');
		Setting::replaceConfigs(['app.name' => 'key2']);
		$this->assertEquals(config('app.name',null),'value2');
	}

	
}