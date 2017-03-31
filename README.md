# JsonData behavior for Yii2 ActiveRecords
Enables you to easily encode and decode json in your activerecords. It automatically encodes before validating and saving to database and decodes after.

[![Latest Stable Version](https://poser.pugx.org/Locustv2/yii2-json-data-behavior/v/stable)](https://packagist.org/packages/Locustv2/yii2-json-data-behavior)
[![Total Downloads](https://poser.pugx.org/Locustv2/yii2-json-data-behavior/downloads)](https://packagist.org/packages/Locustv2/yii2-json-data-behavior)
[![Latest Unstable Version](https://poser.pugx.org/Locustv2/yii2-json-data-behavior/v/unstable)](https://packagist.org/packages/Locustv2/yii2-json-data-behavior)
[![License](https://poser.pugx.org/Locustv2/yii2-json-data-behavior/license)](https://packagist.org/packages/Locustv2/yii2-json-data-behavior)


## Installation

The preferred way to install the library is through [composer](https://getcomposer.org/download/).

Either run
```
php composer.phar require --prefer-dist locustv2/yii2-json-data-behavior
```

or add
```json
{
    "require": {
        "locustv2/yii2-json-data-behavior": "~1.0.0"
    }
}
```
to your `composer.json` file.

## Usage

```php
public function behaviors()
{
    return [
        'class' => \locustv2\behaviors\JsonDataBehavior::className(),
        'dataAttribute' => 'hotel_data',
    ];
}
```
After configuring your activerecord as above, you can use as follows:
```php
$model = Hotel::find()->one();

var_dump($model->getData('rooms')); // assume it returns a list of rooms
var_dump($model->getData('rooms.0.price')); // to get rooms data

$model->setData('ratings', [
    '5star' => ['count' => 100],
    '4star' => ['count' => 200],
    '3star' => ['count' => 20],
    '2star' => ['count' => 75],
    '1star' => ['count' => 50],
]);

var_dump($model->getData('ratings.3star.count')); // returns 20
```


## To do
 - Add unit tests

## Contributing
Feel free to send pull requests.


## License
For license information check the [LICENSE](LICENSE.md)-file.
