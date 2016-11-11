<?php

namespace aracoool\uuid;

use yii\behaviors\AttributeBehavior;
use yii\db\BaseActiveRecord;

/**
 * Class UuidBehavior
 * @package aracoool\uuid
 */
class UuidBehavior extends AttributeBehavior
{
    /**
     * @var string
     */
    public $defaultAttribute = 'id';

    /**
     * @inheritdoc
     */
    public function init()
    {
        if (!$this->attributes) {
            $this->attributes = [
                BaseActiveRecord::EVENT_BEFORE_INSERT => [$this->defaultAttribute]
            ];
        }
    }

    /**
     * @param \yii\base\Event $event
     * @return mixed|string
     */
    protected function getValue($event)
    {
        $this->value = Uuid::v4();
        return $this->value;
    }
}