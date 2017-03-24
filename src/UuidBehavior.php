<?php

namespace aracoool\uuid;

use yii\base\InvalidConfigException;
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
     * @var string
     */
    public $version;
    /**
     * If you will use Uuid version 3 or 5, this field should be set
     * @var string
     */
    public $namespace;
    /**
     * If you will use Uuid version 3 or 5, this field should be set
     * Set attribute name whose value will be used as second parameter for Uuid::v3() or Uuid::v5()
     * @var string
     */
    public $nameAttribute;

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
     * @throws \InvalidArgumentException
     * @throws \yii\base\InvalidConfigException
     */
    protected function getValue($event)
    {
        if ($this->version == Uuid::V4) {
            $this->value = Uuid::v4();
            return $this->value;
        }

        if (empty($this->namespace) || empty($this->nameAttribute)) {
            throw new InvalidConfigException('Fields "namespace" and "nameAttribute" should be set');
        }

        if(!method_exists(Uuid::class, $this->version)) {
            throw new InvalidConfigException('Invalid version of UUID');
        }

        $this->value = call_user_func([Uuid::class, $this->version], $this->nameAttribute, $this->nameAttribute);
        return $this->value;
    }
}