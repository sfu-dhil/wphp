# Women's Print History Project

[Women's Print History Project][wphp] (affectionately known as WPHP) is a PHP application written using the
[Symfony Framework][symfony]. It is a digital tool for collecting data about women
in the print and publishing industry in England,

## Requirements

We have tried to keep the requirements minimal. How you install these
requirements is up to you, but we have [provided some recommendations][setup]

- Apache >= 2.4
- PHP >= 7.4
- Composer >= 2.0
- MariaDB >= 10.8[^1]
- Yarn >= 1.22

## Installation

1. Fork and clone the project from [GitHub][github-wphp].
2. Install the git submodules. `git submodule update --init` is a good way to do this
3. Install composer dependencies with `composer install`.
4. Install yarn dependencies with `yarn install`.
4. Create a MariaDB database and user.

   ```sql
    DROP DATABASE IF EXISTS wphp;
    CREATE DATABASE wphp;
    DROP USER IF EXISTS wphp@localhost;
    CREATE USER wphp@localhost IDENTIFIED BY 'abc123';
    GRANT ALL ON wphp.* TO wphp@localhost;
    ```
5. Copy .env to .env.local and edit configuration to suite your needs.
6. Either 1) create the schema and load fixture data, or 2) load a MySQLDump file
   if one has been provided.
  1. ```bash
        php ./bin/console doctrine:schema:create --quiet
        php ./bin/console doctrine:fixtures:load --group=dev --purger=fk_purger
      ``` 
    2. ```bash
        mysql wphp < wphp.sql
      ``` 

7. Visit http://localhost/wphp
8. happy coding!

Some of the steps above are made easier with the included [MakeFiles](etc/README.md) 
which are in a git submodule. If you missed step 2 above they will be missing.

[wphp]: https://womensprinthistoryproject.com
[symfony]: https://symfony.com
[github-bep]: https://github.com/sfu-dhil/wphp
[setup]: https://sfu-dhil.github.io/dhil-docs/dev/

[^1]: A similar version of MySQL should also work, but will not be supported.
