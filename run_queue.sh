#!/bin/bash

docker exec laravel_app php artisan queue:work 
