Lightspeed data provider for Yii2
================================

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require --prefer-dist prestachamps/lightspeed-data-provider "~1.0"
```

or add

```
"prestachamps/lightspeed-data-provider": "~1.0"
```

to the require section of your `composer.json` file.

Usage
------------

```php
        $dataProvider = new LightspeedDataProvider([
            'apiLanguage' => 'en',
            'apiServer'   => 'eu1',
            'apiKey'      => 'YOUR_API_KEY',
            'userSecret'  => 'USER_SECRET',
            'entity'      => 'products' // type of entity you want to fetch
        ]);
```