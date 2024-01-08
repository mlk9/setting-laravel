# Setting Laravel
Setting (encryption) for Laravel <br>
Encryption docs https://laravel.com/docs/encryption

## Requirement
- Laravel 6+
- PHP +8.2
## Installation
- via composer
```sh
composer require mlk9/setting-laravel
```
- vendor publish
```sh
php artisan vendor:publish --tag=setting-laravel
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
destroy data
```sh
Setting::destroy('KEY_NAME'); 
```
destroy all data
```sh
Setting::destroyAll(); 
```
get  all data
```sh
Setting::all(); 
```
refresh salts
```sh
Setting::refreshSalts();
```

### config using
#### Method 1 (recommended)
replace all config data with setting data
```sh
//uses at \app\Providers\AppServiceProvider.php in boot
Setting::replaceAllConfigs();
```
#### Method 2 (customize)
replace custom config data with setting data
```sh
//in page save
Setting::set(['seo.site.name' => 'Maleki']); 
//uses at \app\Providers\AppServiceProvider.php in boot
Setting::replaceConfigs(['app.name' => 'seo.site.name']);
//for test
config('app.name') // return : Maleki
```
#### Method 3 (not recommended)
`app\Providers\AppServiceProvider.php`
```sh
public function boot()
   {
    Config::set('services.example.exam1',Setting::get('example.exam'));
   }
```