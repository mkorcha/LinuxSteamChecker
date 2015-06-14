#!/bin/sh

echo "Installing composer dependencies..."
cd app && composer install

echo "Installing node modules..."
cd ../ && npm install --save-dev

echo "Running the initial crawler..."
php app/crawler.php

echo "Everything should be good to go!"