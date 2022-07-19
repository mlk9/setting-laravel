# Setting Laravel
Setting (encryption) for Laravel <br>
Encryption docs https://laravel.com/docs/encryption

## Requirement
- Laravel 6+
## Installation
- via composer
```sh
composer require mlk9/setting-laravel
```
- vendor publish
```sh
php artisan vendor:publish --tag=setting
```
- migrate table
```sh
php artisan migrate
```
## Usage
set data
```sh
Setting::set('KEY_NAME','VALUE'); 
//or
Setting::set([
'KEY_NAME' => 'VALUE',
'KEY_NAME' => 'VALUE'
]); 
```
get data
```sh
Setting::get('KEY_NAME','DEFAULT_VALUE'); 
```
key exist
```sh
Setting::exists('KEY_NAME'); //output : bool
```
### config using
`app\Providers\AppServiceProvider.php`
```sh
public function boot()
   {
    Config::set('services.example.exam1',Setting::get('example.exam'));
   }
```
## Todo
- load automaticly configs
