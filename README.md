# Cloudwatch logging para Laravel & Lumen

## Instalación
En composer.json, agregar el repositorio y la dependencia.

Repositorio:
```
        "astroselling/laravel-cloudwatch-logging": {
            "type": "git",
            "url": "git@github.com:astroselling/laravel-cloudwatch-logging.git"
        },
```
Dependencia:
```
        "astroselling/laravel-cloudwatch-logging": "^0.1",
```

En el .env, agregar credenciales para AWS y la configuración para los logs "batch size" y "log level":
```
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=sa-east-1

CLOUDWATCH_BATCH_SIZE=3
CLOUDWATCH_LOG_LEVEL=debug
CLOUDWATCH_LOG_RETENTION=14
```

En el archivo de configuración `logging.php` agregar los canales que envían los logs a cloudwatch:

```
        'cloudwatch' => [
            'driver' => 'custom',
            'via' => \Astroselling\LaravelCloudwatchLogging\CloudWatchLoggerFactory::class,
            'name' => config('app.name'),
            'sdk' => [
              'region' => env('AWS_DEFAULT_REGION', 'sa-east-1'),
              'version' => 'latest',
              'credentials' => [
                'key' => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY')
              ],
            ],
            'streamName' => 'app_default',
            'retention' => env('CLOUDWATCH_LOG_RETENTION', 14),
            'level' => env('CLOUDWATCH_LOG_LEVEL', env('LOG_LEVEL', 'debug')),
            'batch_size' => env('CLOUDWATCH_BATCH_SIZE', 1),
        ],
```



## Uso

Como cualquier log de Monolog:
`Log::channel('canal_de_cloudwatch)->debug('Este es el mensaje', $array_con_data);`

Los logs se alamcenarán en Cloudwatch en un Log Group nombreado de la siguiente forma:
`nombre_de_la_aplicación-entorno`
Por ejemplo:
`atroselling3.0-production`, `astroselling3.0-local` o `TitanHub-staging`.

Dentro de ese Log Group, se agrupan en 'Streams', el nombre del stream será el que se indique en la configuración del canal (en logging.php) bajo la key "streamName". En el ejemplo anterior, el stream donde se guardarán los logs será "app_default".