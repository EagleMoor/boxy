# boxy
Boxy component

## yii\boxy\behavior\Redis

for install add to `config/web.php` and `config/console.php`

```php
    'on beforeRequest' => function($event) {
        Yii::$app->redis->attachBehavior('redis', 'yii\boxy\behavior\Redis');
    }
```