# Client booking app

This is a client booking management app, you can use it, to book appointments for your clients.

## Start the app

You will need php 8.2, composer 2+ and Docker on your machine.

First install dependencies
```shell
$ composer install
```

Copy `.env.example` to `.env`  
Then generate an app key
```shell
$ php artisan key:generate
```

Run the application
```shell
$ ./vendor/bin/sail up -d
```

Enter the app's container

```shell
$ ./vendor/bin/sail shell
```

Run database migration

```shell
$ php artisan migrate
```

Insert the opening hours with seeder
```shell
$ php artisan db:seed
```

Open `http://localhost` from your browser 🎉  
_If your 80 port is busy, add an `APP_PORT` row to your `.env` file._

## Testing

```shell
$ ./vendor/bin/phpunit
```

## Debug

If you want use xdebug with breakpoints in your IDE, uncomment the row `SAIL_XDEBUG_MODE` in the `.env` file.
