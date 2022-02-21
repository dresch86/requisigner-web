## About Requisigner

Requisigner is a Laravel-based webapp for managing an enterprise level document flow. Documents can be uploaded and shared
with others for signing.

## Installing

Requisigner is designed to be run inside a Docker ecosystem, but can be configured to work with a standard LAMP environment.

### Requirements

1. PHP
2. Composer
3. NPM (for building only)

### Procedure

1. Pull Github repository
2. Issue command ```composer install```
3. Copy sample (.env.sample) config with ```cp .env.sample .env```
4. Edit, at minimum, the database settings (i.e. user, password, database name)
5. Make sure config file has ```chmod 640``` for security
6. Issue ```php artisan migrate``` to build the database
7. Issue ```php artisan db:seed --class="AdministratorSeeder"``` to create the initial admin user.
8. Issue ```php artisan db:seed --class="GroupSeeder"``` to create the initial "Root" group.
9. Navigate to the public URL / IP address configured in .env and login!

## License

Requisigner is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
