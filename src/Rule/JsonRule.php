<?php

namespace hunomina\Validator\Json\Rule;

class JsonRule extends Rule
{
    public const OBJECT_TYPES = ['entity', 'object'];

    public function validate($data): bool
    {
        if (parent::validate($data)) {
            return true;
        }

        if (in_array($this->type, self::OBJECT_TYPES, true)) { // json object is a non empty array
            return (is_array($data) && !empty($data));
        }

        return false;
    }
}