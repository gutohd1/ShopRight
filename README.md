# Vanilla php - ShopRight
## Intro:
This project runs plain PHP 8 and implement one API with 2 endpoints and one Admin page

As part of the requirements 2 JSON files were implemented as DB tables and Session as caching.
However, to be able to use the best of API and view also one REDIS server were implemented and consumed.
Because of that, session variable was used as fallback option before resourcing to the actual "DB".

Requirements of the application mention "Real Time" but given this is a backend focused application, no JS will be implemented for now.

## Requirements:
- Docker

## Instructions:
### Setup
- Load the containers by running:
```bash
docker compose up -d 
```
- Composer install on the container:
```bash
docker compose run php-shop-right composer install
```
### Running
URL: ```http://localhost:8880```
- Api endpoints:
  - POST: ```/api/order/create```
    - ```products: [{"product_id":2,"quantity":2,"price":14.99}, ...]```
  - GET: ```/api/session/clear```
- Admin page:
    - ```/admin```

## Logs
- Logs are logged on ```app/storage/logs/error.log```
logs file are not formatted as JSON to keep the writing simply. Each new row of the error.log is of JSON format. 
## Tests
So far no tests were implemented
