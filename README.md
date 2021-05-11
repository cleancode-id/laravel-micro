# Laravel Micro
Lightweight Laravel, built for microservices.

## Features
- PHP 8.
- Laravel 8.
- Optimized for micro-API Backend, large-scale app.
- Authentication using JWT token driver (validate, verify sign).
- Unit & Feature Test (To Do).
- Standard Coding Style & Clean Code.
- Authorization & Policies (To Do).
- Docker for containerization & orchestration.
- Database replication (master-slave) default connection.

## Setup for Development
- Download latest release from https://github.com/cleancode-id/laravel-micro/releases
- Enter `src/` directory, and run `composer install` for installing dependencies.
- Copy `.env.example` to `.env` and set your database connection details
- Run `php artisan serve`

## Build, Ship, Run Anywhere
Build docker image
```
docker build -t my-service:1.0 -f docker/Dockerfile .
```
Push to Registry
```
docker push my-service:1.0
``` 
Run!
```
docker run -p 8080:8080 -e APP_NAME="My Service" -e DB_CONNECTION="mysql" -e DB_HOST="172.17.0.1" my-service:1.0
```
Run as queue worker
```
docker run -p 8080:8080 -e APP_NAME="My Service" -e DB_CONNECTION="mysql" -e DB_HOST="172.17.0.1" -e CONTAINER_ROLE="queue" my-service:1.0
```
Run as scheduler
```
docker run -p 8080:8080 -e APP_NAME="My Service" -e DB_CONNECTION="mysql" -e DB_HOST="172.17.0.1" -e CONTAINER_ROLE="scheduler" my-service:1.0
```