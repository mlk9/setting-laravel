# Config Storage Laravel
Config storage by key (encryption) for the Laravel<br>
Encryption docs https://laravel.com/docs/8.x/encryption
## Requirement
- Laravel 6+
## Installation
- vendor publish 
```sh
php artisan vendor:publish --tag=dbconfig
```
- migrate table
```sh
php artisan migrate
```
## Usage
- set data
```sh
app('dbconfig')->set('KEY_NAME','VALUE'); 
```
- get data
```sh
app('dbconfig')->get('KEY_NAME','DEFAULT_VALUE'); 
```
- key exist
```sh
app('dbconfig')->get('KEY_NAME'); //output : bool
```
