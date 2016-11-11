<?php

namespace aracoool\uuid;

use yii\validators\Validator;

/**
 * Class UuidValidator
 * @package aracoool\uuid
 */
class UuidValidator extends Validator
{
    /**
     * @var string
     */
    public $pattern = '/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/';

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->message === null) {
            $this->message = \Yii::t('app', '{attribute} is not UUID string');
        }
    }

    /**
     * @inheritdoc
     */
    public function validateValue($value)
    {
        $valid = true;
        if (!is_string($value)) {
            $valid = false;
        } elseif (!preg_match($this->pattern, $value)) {
            $valid = false;
        }

        return $valid ? null : [$this->message, []];
    }
}