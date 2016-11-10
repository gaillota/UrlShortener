Shortener
============
A simple shortener with QR Code and analytics on links

## Features
* Shorten URL using your own domain name (the shorter the better)
* Recover a lost QR Code if you didn't save it
* Create an account to keep track of your links
* Custom your links with your own text (ex: http://shorten.er/custom)
* Analyze the usage of each link by logging every clicks (date, browser, country, ip, etc.)

## Technologies
* Symfony 2
* Bootstrap 3
* Composer
* [Highcharts.com](http://www.highcharts.com)

## Requirements
* Apache or Nginx
* PHP > 5.3.9
* MySQL
* Composer

## Installation
1. Clone repository
* `git clone https://github.com/gaillota/UrlShortener.git`
2. Install dependencies
* `composer install`
3. Edit the `app/config/parameters.yml` file
    * /!\ Don't forget to edit the `admin.email` and `admin.password` values
4. Create the database
 * `php app/console doctrine:database:create`
5. Create the schema
 * `php app/console doctrine:schema:create --force`
6. Boot the app by visiting `/boot`
7. You're all set !

---

A Symfony project created on March 19, 2016, 1:23 pm.
