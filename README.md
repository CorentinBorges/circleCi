# BileMo API

<a href="https://codeclimate.com/github/CorentinBorges/bileMo/maintainability"><img src="https://api.codeclimate.com/v1/badges/00d77ce3da0f8fc0245c/maintainability" /></a>

The BileMo API to have the best phone catalogue

## Required

* Symfony 5.1
* WAMP Server
    * PHP 7.4
    * MySQL 5.7
    * composer 1.10

## Installing project

1.  Download:
    ```bash
    $ git clone https://github.com/CorentinBorges/bileMo.git
    ```

2.  Install:
    ```
    $ composer install
    ```

3.  Configure your database in [.env](.env) 
    ```
    DATABASE_URL= mysql://username:password@host:port/dbName?serverVersion=5.7
    ```
    
4.  Create the database and export fixtures (pass for all clients: 'ClientBilemo0', pass Admin:'AdminBilemo0' )
    ```bash
    php bin/console doctrine:database:create
    php bin/console doctrine:migration:migrate
    php bin/console doctrine:fixtures:load
    ```

5.  Configure your JWT token with SSH key (this is an exemple) :
    - Enter this command lines
    ```
    $ mkdir -p config/jwt
    $ openssl genpkey -out config/jwt/private.pem -aes256 -algorithm rsa -pkeyopt rsa_keygen_bits:4096
    $ openssl pkey -in config/jwt/private.pem -out config/jwt/public.pem -pubout
    ```
    - Adapt your config in [lexik_jwt_authentication.yaml](config/packages/lexik_jwt_authentication.yaml)
    - Enter your passphrase in your [.env](.env) file
    ```
    JWT_SECRET_KEY=%kernel.project_dir%/config/jwt/private.pem
    JWT_PUBLIC_KEY=%kernel.project_dir%/config/jwt/public.pem
    JWT_PASSPHRASE=Your_SSH_Pass
    ```
    
6. Connect the website locally with symfony:
    ```
    symfony serve -d
    ```
Your url must be 127.0.0.1:8000 if you don't have any other projects running with symfony at the same time.

## API Documentation
Access to api documentation: host:port/api/doc

## Built With
*   [Symfony 5.1](https://symfony.com/)
*   [Composer 1.10.5](https://getcomposer.org/)
*   [Twig 3.0.3](https://twig.symfony.com/)
*   [Doctrine 3.3](https://www.doctrine-project.org/index.html)
*   [JWT authentication 2.9.0](https://github.com/lexik/LexikJWTAuthenticationBundle/blob/master/Resources/doc/index.md#installation)