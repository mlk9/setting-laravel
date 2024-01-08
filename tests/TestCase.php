<?php

namespace Mlk9\SettingPackage\Tests;

use Mlk9\Setting\SettingServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
  public function setUp(): void
  {
    parent::setUp();
    // additional setup
    $this->artisan('migrate', 
                      ['--database' => 'testbench'])->run();
  }

  protected function getPackageProviders($app)
  {
    return [
        SettingServiceProvider::class,
    ];
  }

  protected function getEnvironmentSetUp($app)
  {
    // perform environment setup
    // Setup default database to use sqlite :memory:
    $app['config']->set('database.default', 'testbench');
    $app['config']->set('database.connections.testbench', [
        'driver'   => 'sqlite',
        'database' => ':memory:',
        'prefix'   => '',
    ]);

    // import the CreatePostsTable class from the migration
  include_once __DIR__ . '/../database/migrations/2021_09_12_000000_create_setting_table.php';

  // run the up() method of that migration class
  (new \CreateSettingTable)->up();
  }

  
}