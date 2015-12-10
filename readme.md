## Telegram BDK

Version < 1.0.0 in beta test, not for use yet.

#### Installation:

1 `composer require zanshee/telegram-bdk`

2 Into `app/Kernel.php` add
```            new Zanshee\TelegramBDKBundle\ZansheeTelegramBDKBundle(),```

3 Into `app/config/config.yml` add
```
zanshee_telegram_bdk:
    <bot name>:
        api_key: "<bot api key>"
```