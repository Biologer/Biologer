# Biologer

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

Biologer is a tool for collecting data on species distribution with the help of community. It started as an effort to collect data on reptiles, amphibians and butterflies in Serbia, but can and will be used for other taxa. It can also can be extended for other territories, as it already has been for Croatia.

You my run the application on your own servers but please note that we offer no support.

Biologer is built using [Laravel framework](https://laravel.com).

## Install

The installation and deployment process is typical for a Laravel application, and it is assumed you know how to do that. More info can be found online if needed.

## Requirements

Following requirements must be installed on the server for the application to work:

- PHP >= 7.4
- OpenSSL PHP Extension
- PDO PHP Extension
- Mbstring PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- Ctype PHP Extension
- JSON PHP Extension
- GD PHP Extension
- Imagick library and PHP Extension

You can check if requirements are installed on your server by opening `requirements.php` file in you browser (located in `public` directory).

### App

Download the master branch

```bash
git clone https://github.com/Biologer/Biologer.git
```

Install the composer dependencies

```bash
composer install
```

Make a copy of `.env.example` and rename to `.env`, adjust the environment variables.

Finally make sure you have set database configurations, then run migrations and seed the data:

```bash
php artisan migrate --seed
```

Install Laravel Passport clients and keys with:

```bash
php artisan passport:install
```

For time and resource consuming jobs such as sending emails, processing photos, importing and exporting data, queues are used. To start queue worker run:

```bash
php artisan queue:work --tries 3
```

You can use something like `supervisord` or `pm2` to make sure the process stays alive. If such tools are unavailable, you can run `start-queue.sh` script with a Cron job.

### Assets

#### Front-end

Installing Biologer's front-end dependencies requires `yarn`.

```
yarn
```

Biologer uses [Laravel Mix](https://laravel.com/docs/mix) to build assets.
To build assets run:

```bash
yarn run dev
```

Available build tasks are defined in `package.json`

Code ships with assets built for production present in `public` directory by default.

#### DEM

Biologer allows users to import large sets of observations. If some of those are missing elevation, Biologer will try to get it by searching DEM with latitude and longitude. Files holding such information are not distributed with Biologer and need to be downloaded separately from [http://srtm.csi.cgiar.org/](http://srtm.csi.cgiar.org/).

After downloading files for needed areas, place them in a single location without changing the names of the files. Default location for that is `resources/srtm`, but any other path can be used. In case you use a different path, you need to set `SRTM_PATH` in the `.env` file with that path.

### Customization

To add a new territory, add it to configuration in `config/biologer.php` using existing ones as an example and set it to be used in `.env` file. Also, you must provide map in SVG format that contains administrative borders and MGRS 10k fields (located in `resources/maps/mgrs10k`), so it can be used in the app. Check out existing ones to see how the SVG must be structured.

## License

Biologer is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
