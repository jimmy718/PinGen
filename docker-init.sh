#!/bin/bash

echo 'Installing Pin Gen'

if [ -x "$(command -v docker)" ]; then
    echo "Found Docker installed"
else
    echo "Please Install Docker and rerun"
    exit 1
fi

if [ -x "$(command -v docker-compose)" ]; then
    echo "Found Docker Compose installed"
else
    echo "Please Install Docker Compose and rerun"
    exit 1
fi

echo "Copying .env file"
cp .env.example .env

echo "Starting up Docker containers"
docker-compose up -d

echo "Installing PHP dependencies"
docker-compose exec app composer install

echo "Installing JavaScript dependencies"
docker-compose exec node npm install

echo "Building JavaScript"
docker-compose exec node npm run dev

echo "Generating Application Key"
docker-compose exec app php artisan key:generate

echo "Migrating Database"
docker-compose exec app php artisan migrate

echo "Seeding Database - Generating PINs"
docker-compose exec app php artisan db:seed

echo "Running PHP tests"
docker-compose exec app ./vendor/bin/phpunit

echo "Application up & running at http://localhost:8000"
echo "To bring the application down, run 'docker-compose stop'"
