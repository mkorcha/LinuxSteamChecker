Linux Steam Checker
===================

A simple website to check how much of your Steam library is natively Linux compatible. Good for those who want to see what games they can take with them if they were to switch.

A small project that let me play with PHP a bit again and learn a bit about Redis.

Setup
-----

### Requirements

* Redis
* PHP 5.3+ (and Composer)
* npm

### Setup

There's a nice `setup.sh` included in the root of the repo. If you want to do it yourself (assuming Redis is already running)...

1. Run `composer install` in the `app/` directory
2. Run the crawler: `php app/crawler.php`
3. Install the needed node modules for gulp: `npm install --save-dev`
4. Build the static files: `gulp sass js`

There's a PHP server that you can start with `gulp debug`. If you want to actively work on it, use the default `gulp` task - it watches for chances to SASS and JS files and builds them automatically.

### Configuration

There should be a `config.php` file in the `app/` directory that looks similar to the following:

```
<?php

// Your Steam API key
define("STEAM_API_KEY", "");

// The prefix you want to use for Redis keys
define("REDIS_KEY"    , "");

```