# Config Storage Laravel
Config storage by key (encryption) for the Laravel <br>
Encryption docs https://laravel.com/docs/8.x/encryption

## Requirement
- Laravel 6+
## Installation
- via composer
```sh
composer require mlk9/config-storage-laravel
```
- vendor publish
```sh
php artisan vendor:publish --tag=dbconfig
```
- migrate table
```sh
php artisan migrate
```
## Usage
set data
```sh
DBConfig::set('KEY_NAME','VALUE'); 
//or
DBConfig::set([
'KEY_NAME' => 'VALUE',
'KEY_NAME' => 'VALUE'
]); 
```
get data
```sh
DBConfig::get('KEY_NAME','DEFAULT_VALUE'); 
```
key exist
```sh
DBConfig::exists('KEY_NAME'); //output : bool
```
### config using
`app\Providers\AppServiceProvider.php`
```sh
public function boot()
   {
    Config::set('services.example.exam1',DBConfig::get('example.exam'));
   }
```
## Todo
- load automaticly configs
