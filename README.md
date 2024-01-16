### Requirement
- PHP 8.2
- Composer ^2.4
- Docker
- Ctype PHP Extension
- cURL PHP Extension
- DOM PHP Extension
- Fileinfo PHP Extension
- Filter PHP Extension
- Hash PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PCRE PHP Extension
- PDO PHP Extension
- Session PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- Redis PHP Extension
- PDO_Postgres PHP Extension
- PDO_Mysql PHP Extension (optional)
- GD PHP Extension (optional)
- Imagick PHP Extension (optional)

## Run Quick Setup and don't run the step from 1 to 7
### Quick Setup
Run script below for quick setup
```shell
sh ./setup.sh -p 3000 -a -f -d -r 6380 -b 5433 -e local
```

| Option | Description | Value |
| --- | --- | -- |
| `-p` | Specify custom app port. Default is 3000 | Number | 
| `-a` | Specify has authentication module | Empty |
| `-f` | Specify has file upload feature  | Empty |
| `-d` | Specify docker is development environment if local machine don't install PHP and Composer | Empty |
| `-r` | Specify custom redis port. Default is 6379 | Number |
| `-b` | Specify custom database port. Default is 5432 | Number |
| `-e`  | Specify custom environment (`local`,`development`,`staging`,`production`). Default is `local` | string |

**Note**: Please make sure that the file .env.staging and .env.production are exist (check the [encrypt/decrypt](#encryptdecrypt-env-for-staging--production) ) before run the script above

## Run Manual Setup and don't run the Quick Setup
### Manual Setup

1./ Install Package

Select one of methods belows:

With composer
```bash
composer install
```

Without composer
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs
```

2./ Copy .env
```bash
cp .env.development .env
```

2.1/ (Optional) Modify .env on 1st run at local environment
```
APP_ENV=local
```

3./ Start app
```bash
./vendor/bin/sail up -d
```

4./ Generate app key
```bash
./vendor/bin/sail php artisan key:generate --ansi
```

5./ Run migration
```bash
./vendor/bin/sail php artisan migrate
```

6./ (Optional) Create symlink for file storage if you're using local disk upload
```bash
./vendor/bin/sail php artisan storage:link
```


## 7./ (Optional) Generate Passport secret if you're using authentication modules (EMAIL LOGIN, SMS LOGIN...)
```bash
./vendor/bin/sail php artisan passport:keys
```

## After Setup is done (Quick setup or Manual)
### Install OAuth App. Please make sure that all setup above are run successfully
```bash
./vendor/bin/sail php artisan passport:client --password
```
Note*: If you have multiple-authentication models, and when passport asking for provider, just pick the 1st option. It doesn't matter.

Copy your new client_id, client_secret to FE project

## Encrypt/Decrypt env for staging & production

- In order to decrypt env, please get the key from {your_env_name}.key file and  run command below
```bash
./vendor/bin/sail php artisan env:decrypt --env=your_env_name --key=your_key
```
or 
```bash
php artisan env:decrypt --env=your_env_name --key=your_key
```

EX: In order to decrypt staging env, get the key from file `staging.key` and run command below
```bash
./vendor/bin/sail php artisan env:decrypt --env=staging --key=1234567890
```
- In order to encrypt env, please get the key from {your_env_name}.key file and  run command below
```bash
./vendor/bin/sail php artisan env:encrypt --env=your_env_name --key=your_key
```
or
```bash
php artisan env:encrypt --env=your_env_name --key=your_key
```
EX: In order to decrypt staging env, get the key from file `staging.key` and run command below
```bash
./vendor/bin/sail php artisan env:encrypt --env=staging --key=1234567890
```


## API HealthCheck
View in the browser on (replace the 3000 with your port)
```
http://localhost:3000/api/health-check/
```
## API documents
View in the browser on (replace the 3000 with your port)
```
http://localhost:3000/request-docs/
```

## Localization
- To using localization, attach the locale name in `Accept-Language` header in every request. Supporting locales are `en`(English) and `ja` (Japanese)

## Throttler
- Currently, the APIs have Rate Limit at 60 requests/min, calculating by IP (Anonymous) and by ID (Authenticated User)
- Official Docs https://laravel.com/docs/10.x/routing#rate-limiting


## Rollbar
- Rollbar Token is set at key `ROLLBAR_TOKEN`, with level log as `debug`.
- Will be conditional load for each env that input on the Studio. Implement at `app/Providers/AppServiceProvider.php`
- Logging config at `config/logging.php`
- Validate Project Key
    - Via artisan (sail)
      ```bash
      ./vendor/bin/sail php artisan rollbar:validate-key 
      ```
    - or via composer (sail)
      ```bash
      ./vendor/bin/sail composer additional-commands
      ```
- For more details please check the Official Docs https://docs.rollbar.com/docs/laravel

## Scout APM
- SCOUT KEY is set at key `SCOUT_KEY`, with level log as `info`. You could change it by adding `SCOUT_LOG_LEVEL` to your env. Check official docs for more details https://scoutapm.com/docs/php/configuration
- Will be conditional load for each env that input on the Studio. Implement at `app/Providers/AppServiceProvider.php`
- More config could be done by publishing Scout config:
```bash
  php artisan vendor:publish --provider="Scoutapm\Laravel\Providers\ScoutApmServiceProvider"  
```

- For more details please check the Official Docs https://scoutapm.com/docs/php/laravel
