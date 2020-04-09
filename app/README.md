Laravel send notifications
=======================

> php artisan vendor:publish --force
> php artisan migrate

Logging config
=======================

> vim config/logging.php

```php
<?php
return [
    'channels'=>[
        'notification' => [
            'driver' => 'daily',
            'path' => storage_path('logs/notification.log'),
            'level' => 'debug',
            'days' => 14,
        ]
    ]
]
?>
```
