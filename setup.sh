#!/bin/sh

echo "Installing composer dependencies..."
cd app && composer install

echo "Running the initial crawler..."
php app/crawler.php

echo "Installing node modules..."
cd ../ && npm install --save-dev

echo "Building static files..."
gulp js sass

echo "Everything should be good to go!"
