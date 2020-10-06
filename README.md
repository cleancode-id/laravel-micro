# Laravel Micro
Lightweight Laravel, built for microservices.

## Features
- Laravel 8.
- Optimized for micro-API Backend.
- Authentication JWT using 3rd party Auth Service - Identity Management & Access Control (Auth0, Keycloak, etc).
- Unit & Feature Test (To Do).
- Standard Coding Style & Clean Code.
- Authorization & Policies (To Do).
- Docker for containerization & orchestration.

## Setup for Development
- Run `composer create-project --prefer-dist cleancode-id/laravel-micro`
- Edit `.env` and set your database connection details
- Run `php artisan serve`

## Build, Ship, Run Anywhere
Build docker image (change `cleancodeid` to your docker hub).
```
docker build -t cleancodeid/service:1.0 .
```
Push to Registry
```
docker push cleancodeid/service:1.0
``` 
Run!
```
docker run -p 8080:8080 -e APP_NAME="My Service" -e DB_HOST="172.17.0.1" cleancodeid/service:1.0
```
