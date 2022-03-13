## About Requisigner

Requisigner is a Laravel-based webapp for managing an enterprise level document flow. Documents can be uploaded and shared
with others for signing.

## Installing

Requisigner is designed to be run inside a Docker ecosystem, but can be configured to work with a standard LAMP environment.

### Requirements

1. PHP
2. Composer
3. NPM (for building only)

### Running

1. Pull Github repository
2. Copy sample (.env.sample) config with ```cp .env.sample .env```
3. Edit, at minimum, the database settings (i.e. user, password, database name, web server IP, domain)
4. Issue command ```php artisan key:generate``` to generate application key (APP_KEY in .env)
4. Issue command ```composer install```
5. Make sure .env file has ```chmod 640``` for security
6. Issue ```php artisan migrate``` to build the database
7. Issue ```php artisan db:seed --class="AdministratorSeeder"``` to create the initial admin user. A default password will display on the console and should be changed after installation.
8. Issue ```php artisan db:seed --class="GroupSeeder"``` to create the initial "Root" group.'
9. Issue ```php artisan db:seed --class="SettingsSeeder"``` to add default settings.
10. Navigate to the public URL / IP address configured in .env and login!

### Building
1. Pull Github repository
2. Issue command ```npm run production``` to compile assets
3. Access at configured URL in web browser

## License

Requisigner is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
