.. _install:

Installation
============

.. note::

    WPHP doesn't use labeled or numbered releases. The code in the
    master branch of the repository should be runnable.

Make sure the requirements are satisfied.

The WPHP application is based on Symfony 4.4. Installation follows the normal
process for installing a Symfony application.

1. Get the code from GitHub. 

.. code-block:: bash

  git clone https://github.com/sfu-dhil/wphp.git

2. Get the submodules from Git. There is quite a bit of reusable code in the
   application, and it's organized with git submodules.

.. code-block:: bash

  git submodule init
  git submodule update --recursive --remote

3. Create a database and database user.
  
.. code-block:: sql

  create database wphp;
  create user if not exists wphp@localhost;
  grant all on wphp.* to wphp@localhost;
  set password for wphp@localhost = password('abc123');

4. `Install composer`_ if it isn't already installed somewhere.
  
5. Install the composer dependencies. Composer will ask for some 
   configuration variables during installation.
  
.. code-block:: bash

  ./vendor/bin/composer install --no-dev -o
   
Sometimes composer runs out of memory. If that happens, try this alternate.
  
.. code-block:: bash

  php -d memory_limit=-1 ./vendor/bin/composer install --no-dev -o

6. Update file permissions. The user running the web server must be
   able to write to `var/cache/*` and `var/logs/*` and
   `var/sessions/*`. The symfony docs provide `recommended commands`_
   depending on your OS.

7. Please follow the instructions in the config.rst file to set up the configuration settings for this project.
  
8. Load the schema into the database. This is done with the 
   symfony console.
  
.. code-block:: bash

  ./bin/console doctrine:schema:update --force
  
8. Create an application user with full admin privileges. This is also done 
   with the symfony console.
  
.. code-block:: bash

  ./bin/console nines:create:user

9. If you haven't installed npm and yarn globally, you will have to install them. You could do this by running the below commands in the terminal.
  
.. code-block:: bash

  sudo apt install npm
  sudo npm install --global yarn

10. If you have installed npm and yarn globally, then set up yarn for this project by running the below command inside project directory.
  
.. code-block:: bash

  yarn install

11. Configure the web server. The application's `public/` directory must
    be accessible to the world. Symfony provides `example
    configurations`_ for most server setups.

12. Start the Symfony server by using the below command and navigate to the link displayed.
  
.. code-block:: bash

  symfony server:start

At this point, the web interface should be up and running, and you should
be able to login by following the Login link in the top right menu bar.

13. Once everything is done, you should stop the Symfomny server. Before you close the terminal, make sure to stop the server using this command.
  
.. code-block:: bash

  symfony server:stop

.. _`Install composer`: https://getcomposer.org/download/

.. _`recommended commands`:
   http://symfony.com/doc/current/setup/file_permissions.html

.. _`example configurations`:
   http://symfony.com/doc/current/setup/web_server_configuration.html
