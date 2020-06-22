# Laravel Aerospike Session Driver

[Aerospike](http://www.aerospike.com/) Session driver for [Laravel](http://laravel.com/). This package makes it easy to store session data in Aerospike.

## 🔥 Credits
 
- copy pasted blocks from https://github.com/makbulut/laravel-aerospike
- custom session driver tips https://laravel.com/docs/7.x/session#adding-custom-session-drivers

## 📦 Installation

This package includes `aerospike/aerospike-client-php` as a dependency.

So it is sufficient to run composer require to install this package.

```bash
composer require reservio/php-laravel-session-aerospike
```

## 🔧 Configuration

#### Provider

This package uses https://laravel.com/docs/7.x/packages#package-discovery so you don't have to configure service provider.

#### Environment

Change the session driver in .env to aerospike:

```
SESSION_DRIVER=aerospike
```

Add aerospike server information to `.env` file.

```
AEROSPIKE_HOST=1.2.3.4
AEROSPIKE_PORT=3000
AEROSPIKE_NAMESPACE=production
AEROSPIKE_SET=app1
```

#### Configuration file

Uses `session.connections.aerospike` with this format:

```
'connections' => [
    'aerospike' => [
        'set' => env('AEROSPIKE_SET'),
        'namespace' => env('AEROSPIKE_NAMESPACE'),
        'hosts' => [
            [
                'addr' => env('AEROSPIKE_HOST', 'localhost'),
                'port' => (int) env('AEROSPIKE_PORT', 3000)
            ]
        ],
    ]
]
```

For more information about Session, check https://laravel.com/docs/session.

## Docker

Inspiration on how to add aerospike to your docker image.

```bash
RUN find /app/vendor/aerospike/aerospike-client-php/ -name "*.sh" -exec chmod +x {} \;
RUN cd /app/vendor/aerospike/aerospike-client-php/ && composer run-script post-install-cmd
RUN cd /app/vendor/aerospike/aerospike-client-php/src && make install

RUN echo "extension=aerospike.so" >> /opt/docker/etc/php/php.ini
RUN echo "aerospike.udf.lua_user_path=/usr/local/aerospike/usr-lua" >> /opt/docker/etc/php/php.ini
```

## 📄 License

This package is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)