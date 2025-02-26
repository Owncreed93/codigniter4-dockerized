# Project

## Turn on project
```bash
docker compose up --build -d
```
- Fails the first time

##  Add Project permission
```bash
sudo chown -R owncreed93 php-codeigniter-test
```

## Go inside the project
```bash
docker exec -it testing_codeigniter /bin/bash
```

## Install codeigniter
```bash
composer install
```

## Change .env CI-Environment
```bash
CI_ENVIRONMENT = development
```

## Create the file for migration
```bash
docker exec -it <container_name> php spark make:migration CreateProductosTable
docker exec -it testing_codeigniter php spark make:migration CreateProductosTable
```

## Execute the migration
```bash
docker exec -it <container_name> php spark migrate
docker exec -it testing_codeigniter php spark migrate
```