<?php
/**
 * @link https://github.com/Locustv2/yii2-json-data-behavior
 * @copyright Copyright (c) 2017 locustv2
 * @license https://github.com/Locustv2/yii2-json-data-behavior/blob/master/LICENSE.md
 */

namespace locustv2\behaviors;

use yii\db\ActiveRecord;
use yii\base\InvalidConfigException;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;

/**
 * JsonDataBehavior provides the ability to use json data from your tables easily.
 * It automatically encodes before validating and saving to database and decodes after.
 * Example:
 * ```php
 * public function behaviors()
 * {
 *     return [
 *         'class' => \locustv2\behaviors\JsonDataBehavior::className(),
 *         'dataAttribute' => 'hotel_data',
 *     ];
 * }
 * ```
 * After configuring your activerecord as above, you can use as follows:
 * ```php
 * $model = Hotel::find()->one();
 *
 * var_dump($model->getData('rooms')); // assume it returns a list of rooms
 * var_dump($model->getData('rooms.0.price')); // to get rooms data
 *
 * $model->setData('ratings', [
 *     '5star' => ['count' => 100],
 *     '4star' => ['count' => 200],
 *     '3star' => ['count' => 20],
 *     '2star' => ['count' => 75],
 *     '1star' => ['count' => 50],
 * ]);
 *
 * var_dump($model->getData('ratings.3star.count')); // returns 20
 * ```
 *
 * @author Yuv Joodhisty <locustv2@gmail.com>
 * @since 1.0
 */
class JsonDataBehavior extends \yii\base\Behavior
{
    /**
     * @var string the data attribute that contains the json to be encoded/decoded
     */
    public $dataAttribute;

    /**
     * @inheritdoc
     */
    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'encodeData',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'encodeData',
            ActiveRecord::EVENT_BEFORE_VALIDATE => 'encodeData',
            ActiveRecord::EVENT_AFTER_FIND => 'decodeData',
            ActiveRecord::EVENT_AFTER_INSERT => 'decodeData',
            ActiveRecord::EVENT_AFTER_REFRESH => 'decodeData',
            ActiveRecord::EVENT_AFTER_UPDATE => 'decodeData',
            ActiveRecord::EVENT_AFTER_VALIDATE => 'decodeData',
        ];
    }

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();

        if(empty($this->dataAttribute)) {
            throw new InvalidConfigException('"'.$this->className().'::$dataAttribute" must be specified.');
        }
    }

    /**
     * Retrieves the value from the data attribute of the owner that contains the json data.
     * @param string $key the data to be retrieved
     * @return mixed the value from the json data
     * @see \yii\helpers\ArrayHelper::getValue()
     */
    public function getData($key)
    {
        return ArrayHelper::getValue($this->owner->{$this->dataAttribute}, $key);
    }

    /**
     * Add or replace data into the data attribute of the owner that contains the json data.
     * @param string $key the key to be added
     * @param mixed $value the value to be added
     */
    public function setData($key, $value)
    {
        $this->owner->{$this->dataAttribute} = ArrayHelper::merge($this->owner->{$this->dataAttribute}, [$key => $value]);
    }

    /**
     * Encodes the data of [[dataAttribute]] of the owner
     * @param \yii\base\Event $event event instance.
     * @see \yii\helpers\Json::encode()
     */
    public function encodeData($event)
    {
        $this->owner->{$this->dataAttribute} = Json::encode($this->owner->{$this->dataAttribute});
    }

    /**
     * Decodes the data of [[dataAttribute]] of the owner
     * @param \yii\base\Event $event event instance.
     * @see \yii\helpers\Json::decode()
     */
    public function decodeData($event)
    {
        $this->owner->{$this->dataAttribute} = Json::decode($this->owner->{$this->dataAttribute});
    }
}
