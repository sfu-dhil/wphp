.. _config:

Configuration
=============

Most of the application is configured with the symfony .env file present in the project directory.
1. You can find this file by listing out all hidden files in the directory using the command below.

.. code-block:: bash

  ls -al

2. Copy the .env file to .env.local using the below command.

.. code-block:: bash

  cp .env .env.local

3. Edit this .env.local file using any editor to make the below configuration changes.

.. code-block:: bash
   
     # Disable the mailer DSN by changing it to null://null
     MAILER_DSN=null://null

     # Set the database configuration for the project: database user name(db_user), password(db_password) and database_name(db_name)
     DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=5.7

     # To use symfony to start the server change ROUTE_BASE to
     ROUTE_BASE=/

     # To set the cookie configuration for the site set ROUTE_HOST to
     ROUTE_HOST=127.0.0.1


