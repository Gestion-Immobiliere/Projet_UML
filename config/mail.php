<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Mailer
    |--------------------------------------------------------------------------
    |
    | Ici on configure le « mailer » par défaut à utiliser. Nous choisissons
    | smtp, qui correspond à Mailtrap dans notre .env.
    |
    */

    'default' => env('MAIL_MAILER', 'smtp'),

    /*
    |--------------------------------------------------------------------------
    | Mailer Configurations
    |--------------------------------------------------------------------------
    |
    | Chaque « mailer » définit un transporteur (smtp, log, array, etc.).
    | Vous pouvez en ajouter ou modifier ici. Le smtp est configuré pour
    | pointer sur Mailtrap via vos variables d’environnement.
    |
    */

    'mailers' => [

        'smtp' => [
            'transport'     => 'smtp',
            'host'          => env('MAIL_HOST', 'smtp.gmail.com'),
            'port'          => env('MAIL_PORT', 587),
            'encryption'    => env('MAIL_ENCRYPTION', 'tls'),
            'username'      => env('MAIL_USERNAME'),
            'password'      => env('MAIL_PASSWORD'),
            'timeout'       => null,
            'auth_mode'     => null,
            'local_domain'  => env('MAIL_EHLO_DOMAIN', parse_url(env('APP_URL', 'http://localhost'), PHP_URL_HOST)),
        ],

        'log' => [
            'transport' => 'log',
            'channel'   => env('MAIL_LOG_CHANNEL'),
        ],

        'array' => [
            'transport' => 'array',
        ],

        // Vous pouvez conserver ou supprimer les autres mailers par défaut
        'ses' => [
            'transport' => 'ses',
        ],

        'postmark' => [
            'transport' => 'postmark',
        ],

        'sendmail' => [
            'transport' => 'sendmail',
            'path'      => env('MAIL_SENDMAIL_PATH', '/usr/sbin/sendmail -bs -i'),
        ],

        'failover' => [
            'transport'   => 'failover',
            'mailers'     => ['smtp', 'log'],
            'retry_after' => 60,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Global "From" Address
    |--------------------------------------------------------------------------
    |
    | Toutes les emails envoyées utiliseront cette adresse et ce nom par défaut.
    |
    */

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'noreply@immo-dakar.local'),
        'name'    => env('MAIL_FROM_NAME', env('APP_NAME', 'Laravel')),
    ],

    /*
    |--------------------------------------------------------------------------
    | Markdown Mail Settings
    |--------------------------------------------------------------------------
    |
    | Si vous utilisez les templates Markdown, vous pouvez personnaliser
    | le thème et le chemin des vues ici.
    |
    */

    'markdown' => [
        'theme' => 'default',
        'paths' => [
            resource_path('views/vendor/mail'),
        ],
    ],

];
