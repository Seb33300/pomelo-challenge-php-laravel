# Pomelo PHP Laravel Challenge


## Requirements

* [Php](http://php.net/) 7.3 or higher
* [Composer](https://getcomposer.org)


## Installation

1. Clone project `git clone https://github.com/Seb33300/pomelo-challenge-php-laravel.git`
2. Install PHP dependencies `composer install`
3. Setup config `cp .env.example .env && php artisan key:generate`
4. Launch web server `php artisan serve`
5. Visit http://127.0.0.1:8000/


## How to use

### Part 1

Use the OpenAPI [documentation page](http://127.0.0.1:8000/api/documentation) or the REST client of your choice to try it.

```
POST http://127.0.0.1:8000/api/transform
```

### Part 2

Visit http://127.0.0.1:8000/github/repositories

You should be able to see search results in a table with pagination links just below.

_Please note that the pagination has been made using Laravel Paginator which rely on Tailwind (by default) for the appearance. Because Tailwind is not loaded in this project, the appearance is a bit broken but previous & next buttons work as expected. You may want to inspect the source code to see all pagination buttons._


## Running Tests

```
php artisan test
```
