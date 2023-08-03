.. _update:

Updates
=======

Applying updates from git shouldn't be difficult.

1. Get the updates from a git remote

.. code-block:: bash
   
  git pull

2. Update the git submodules.

.. code-block:: bash

  git submodule update --recursive --remote

3. Install any updated composer dependencies.

.. code-block:: bash

  php -d memory_limit=-1 ./vendor/bin/composer install -o

4. Apply any database schema updates

.. code-block:: bash

  ./bin/console doctrine:schema:update --force
  
5. Update the web assets.
  
.. code-block:: bash

  bower install

6. Clear the cache 

.. code-block:: bash

  ./bin/console cache:clear --env=prod

That should be it.
