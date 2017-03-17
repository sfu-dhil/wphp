Women's Print History Project
====

The Women’s Print History Project 1750-1836 (WPHP) is the first comprehensive 
bibliographical database of women's contributions to print for one of the most 
convulsive periods in the history of both women’s writing and print. The 
database, with nearly 2,000 person entries and over 8,000 title entries, will 
enable rigorous quantitative analysis of patterns in women's print history.

This application was developed by the Digital Humanities Innovation Lab at 
Simon Fraser University with considerable assistance and support from the SFU
Library.

Installation
----

The WPHP application is based on Symfony 3.2. Installation follows the normal
process for installing a Symfony application.

1. Get the code from GitHub. 
  
  ```bash
  git clone https://github.com/sfu-dhil/wphp.git
  ```

1. Get the submodules from Git. There is quite a bit of reusable code in the
application, and it's organized with git submodules.

  ```bash
  git submodule init
  git submodule update
  ```

1. Create a database and database user.
  
  ```sql
  create database wphp;
  grant all on wphp.* to wphp@localhost;
  set password for wphp@localhost = password('hotpockets');
  ```

1. Install composer locally, mostly 
  following [the instructions](https://getcomposer.org/download/).
  
  ```bash
  php composer-setup.php --install-dir=vendor/bin --filename=composer
  ```
  
1. Install the composer dependencies. Composer will ask for some 
   configuration variables during installation.
  
  ```bash
  ./vendor/bin/composer install -o
  ```
  
  Sometimes composer runs out of memory. If that happens, try this alternate.
  
  ```bash
  php -d memory_limit=-1 ./vendor/bin/composer install -o
  ```

1. Update file permissions. The user running the web server must 
  be able to write to `var/cache/*` and `var/logs/*` and `var/sessions/*`. The symfony
  docs provide [recommended commands](http://symfony.com/doc/current/setup/file_permissions.html).
  depending on your OS.
  
1. Load the schema into the database. This is done with the 
  symfony console.
  
  ```bash
  ./bin/console doctrine:schema:update --force
  ```
  
1. Create an application user with full admin privileges. This is also done 
  with the symfony console.
  
  ```bash
  ./bin/console fos:user:create --super-admin  
  ```
  
1. Install the web assets (bundled CSS and Javascript files). Some assets are 
  managed via Symfony, and some are managed with Assetic. Both commands need to
  run.
  
  ```bash
  ./bin/console assets:install
  ./bin/console assetic:dump
  ```

1. Configure the web server. The application's `web/` directory must
  be accessible to the world. Symfony 
  provides [example configurations](http://symfony.com/doc/current/setup/web_server_configuration.html)
  for most server setups.
  
At this point, the web interface should be up and running, and you should
be able to login by following the Login link in the top right menu bar.

Updates
----

Applying updates from git shouldn't be difficult.

1. Get the updates from a git remote

  ```bash
  git pull
  ```

1. Update the git submodules.

  ```bash
  git submodule update --recursive --remote
  ```

1. Install any updated composer dependencies.

  ```bash
  php -d memory_limit=-1 ./vendor/bin/composer install -o
  ```

1. Apply any database schema updates

  ```bash
  ./bin/console doctrine:schema:update --force
  ```
  
1. Update the web assets.
  
  ```bash
  ./bin/console assets:install
  ./bin/console assetic:dump
  ```

1. Clear the cache 

  ```
  ./bin/console cache:clear --env=prod
  ```

That should be it.
