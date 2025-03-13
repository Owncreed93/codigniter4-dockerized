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

## Change Codeigniter 4 .env
```bash
CI_ENVIRONMENT = development
app.baseURL = 'http://127.0.1.0:8080/' # Usually this url
database.default.hostname = <your_db_container>
database.default.database = <your_db_name>
database.default.username = <your_db_username>
database.default.password = <your_db_passwordname>
database.default.DBDriver = MySQLi # usually Mysqli
database.default.port = <your_db_port>
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