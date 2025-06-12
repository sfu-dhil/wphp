# Women's Print History Project

[Women's Print History Project](https://womensprinthistoryproject.com/) (affectionately known as WPHP) is a PHP application written using the [Symfony Framework](https://symfony.com/). It is a digital tool for collecting data about women in the print and publishing industry in England,

## Requirements

- [Docker Desktop](https://www.docker.com/products/docker-desktop/)
- A copy of the `wphp.sql` database sql file. If you are not sure what these are or where to get them, you should contact the [Digital Humanities Innovation Lab](mailto:dhil@sfu.ca) for access. This file should be placed in the root folder.
- A copy of the blog images. These should be placed directly into the `.data/app/blog_images/` directory (start the application for the first time if you don't see the directory).
- A copy of the data (audio/image/pdf) files. These should be placed directly into the `.data/data/audio`,  `.data/data/image`,  `.data/data/pdf` directory (start the application for the first time if you don't see the data directory).

## Initialize the Application

First you must setup the database for the first time

    docker compose up -d db
    # wait 30 after the command has fully completed
    docker exec -it wphp_db bash -c "mysql -u wphp -ppassword wphp < /wphp.sql"

Next you must start the whole application

    docker compose up -d --build

WPHP will now be available at `http://localhost:8080/`

### Create your admin user credentials

    docker exec -it wphp_app ./bin/console nines:user:create <your@email.address> '<your full name>' '<affiliation>'
    docker exec -it wphp_app ./bin/console nines:user:password <your@email.address> <password>
    docker exec -it wphp_app ./bin/console nines:user:promote <your@email.address> ROLE_ADMIN
    docker exec -it wphp_app ./bin/console nines:user:activate <your@email.address>

example:

    docker exec -it wphp_app ./bin/console nines:user:create test@test.com 'Test User' 'DHIL'
    docker exec -it wphp_app ./bin/console nines:user:password test@test.com test_password
    docker exec -it wphp_app ./bin/console nines:user:promote test@test.com ROLE_ADMIN
    docker exec -it wphp_app ./bin/console nines:user:activate test@test.com

## General Usage

### Starting the Application

    docker compose up -d

### Stopping the Application

    docker compose down

### Rebuilding the Application (after upstream or js/php package changes)

    docker compose up -d --build

### Viewing logs (each container)

    docker logs -f wphp_app
    docker logs -f wphp_db
    docker logs -f wphp_webpack_watcher
    docker logs -f wphp_mail

### Accessing the Application

    http://localhost:8080/

### Accessing the Database

Command line:

    docker exec -it wphp_db mysql -u wphp -ppassword wphp

Through a database management tool:
- Host:`127.0.0.1`
- Port: `13306`
- Username: `wphp`
- Password: `password`

### Accessing Mailhog (catches emails sent by the app)

    http://localhost:8025/

### Database Migrations

Migrate up to latest

    docker exec -it wphp_app make migrate

## Updating Application Dependencies

### Yarn (javascript)

    # add new package
    docker exec -it wphp_webpack_watcher yarn add [package]

    # add new dev package
    docker exec -it wphp_webpack_watcher yarn add -D [package]

    # update a package
    docker exec -it wphp_webpack_watcher yarn upgrade [package]

    # update all packages
    docker exec -it wphp_webpack_watcher yarn upgrade

Note: If you are having problems starting/building the application due to javascript dependencies issues you can also run a standalone node container to help resolve them

    docker run -it --rm -v $(pwd)/public:/app -w /app node:19.5 bash

    [check Dockerfile for the 'apt-get update' code piece of wphp-webpack]

    yarn ...

After you update a dependency make sure to rebuild the images

    docker compose down
    docker compose up -d

### Composer (php)

    # add new package
    docker exec -it wphp_app composer require [vendor/package]

    # add new dev package
    docker exec -it wphp_app composer require --dev [vendor/package]

    # update a package
    docker exec -it wphp_app composer update [vendor/package]

    # update all packages
    docker exec -it wphp_app composer update

Note: If you are having problems starting/building the application due to php dependencies issues you can also run a standalone php container to help resolve them

    docker run -it -v $(pwd):/var/www/html -w /var/www/html php:8.2-apache bash

    [check Dockerfile for the 'apt-get update' code piece of wphp]

    composer ...

After you update a dependency make sure to rebuild the images

    docker compose down
    docker compose up -d

## Tests

First make sure the application and database are started with `docker compose up -d`

### Unit Tests

    docker exec -it wphp_app make test

### Generate Code Coverage

    docker exec -it wphp_app make test.cover
    make test.cover.view

If the coverage file doesn't open automatically you can manually open it `coverage/index.html`

## Misc

### PHP Code standards

See standards errors

    docker exec -it wphp_app make lint-all
    docker exec -it wphp_app make symlint

    # or
    docker exec -it wphp_app make stan
    docker exec -it wphp_app make twiglint
    docker exec -it wphp_app make twigcs
    docker exec -it wphp_app make yamllint
    docker exec -it wphp_app make symlint


Automatically fix some standards errors

    docker exec -it wphp_app make fix.all

### Debug helpers

    docker exec -it wphp_app make dump.autowire
    docker exec -it wphp_app make dump.container
    docker exec -it wphp_app make dump.env
    docker exec -it wphp_app make dump.params
    docker exec -it wphp_app make dump.router
    docker exec -it wphp_app make dump.twig

# RDF & jsonld exports

    docker exec -it wphp_app php -d memory_limit=1G ./bin/console wphp:export nines --no-debug
    docker exec -it wphp_app php -d memory_limit=1G ./bin/console wphp:export 18thConnect --no-debug
    docker exec -it wphp_app php -d memory_limit=1G ./bin/console wphp:export jsonld --no-debug
    docker exec -it wphp_app php -d memory_limit=1G ./bin/console wphp:export rdfxml --no-debug