<?php

namespace hunomina\Validator\Json\Rule\Json;

use hunomina\Validator\Json\Exception\Json\InvalidDataException;
use hunomina\Validator\Json\Rule\Json\Traits\EmptyCheckTrait;
use hunomina\Validator\Json\Rule\Json\Traits\EnumCheckTrait;
use hunomina\Validator\Json\Rule\Json\Traits\LengthCheckTrait;
use hunomina\Validator\Json\Rule\Json\Traits\MaximumCheckTrait;
use hunomina\Validator\Json\Rule\Json\Traits\MinimumCheckTrait;
use hunomina\Validator\Json\Rule\Json\Traits\NullCheckTrait;

class TypedListRule extends JsonRule
{
    use NullCheckTrait;
    use LengthCheckTrait;
    use MinimumCheckTrait;
    use MaximumCheckTrait;
    use EnumCheckTrait;
    use EmptyCheckTrait;

    private JsonRule $childRule;

    public function __construct(JsonRule $childRule)
    {
        $this->childRule = $childRule;
    }

    /**
     * @param $data
     * @return bool
     * @throws InvalidDataException
     */
    public function validate($data): bool
    {
        if ($data === null) {
            if (!$this->nullable) {
                throw new InvalidDataException('Can not be null', InvalidDataException::NULL_VALUE_NOT_ALLOWED);
            }
            return true;
        }

        if (!is_array($data)) {
            throw new InvalidDataException('Must be an array', InvalidDataException::INVALID_DATA_TYPE);
        }

        if (!$this->validateEmptyness($data)) {
            throw new InvalidDataException('Can not be empty', InvalidDataException::EMPTY_VALUE_NOT_ALLOWED);
        }

        if (!$this->validateLength($data)) {
            throw new InvalidDataException('Invalid length: Must be ' . $this->length . '. Is ' . count($data), InvalidDataException::INVALID_LENGTH);
        }

        if (!$this->validateMinimum($data)) {
            throw new InvalidDataException('Must contain at least ' . $this->minimum . ' elements. Contains ' . count($data), InvalidDataException::INVALID_MIN_VALUE);
        }

        if (!$this->validateMaximum($data)) {
            throw new InvalidDataException('Must contain at most ' . $this->maximum . ' elements. Contains ' . count($data), InvalidDataException::INVALID_MAX_VALUE);
        }

        foreach ($data as $key => $value) {
            try {
                $this->childRule->validate($value);
            } catch (InvalidDataException $e) {
                throw new InvalidDataException('Element at index ' . $key . ' is invalid : ' . $e->getMessage(), InvalidDataException::INVALID_TYPED_LIST_ELEMENT, $e);
            }
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function validateEmptyness(array $data): bool
    {
        if ($this->empty === false) {
            return count($data) !== 0;
        }

        return true;
    }

    /**
     * @inheritDoc
     */
    public function validateLength(array $data): bool
    {
        if ($this->length === null) {
            return true;
        }

        return $this->length === count($data);
    }

    /**
     * @param array $data
     * @return bool
     */
    public function validateMaximum(array $data): bool
    {
        if ($this->maximum === null) {
            return true;
        }

        return count($data) <= $this->maximum;
    }

    /**
     * @param array $data
     * @return bool
     */
    public function validateMinimum(array $data): bool
    {
        if ($this->minimum === null) {
            return true;
        }

        return count($data) >= $this->minimum;
    }

    /**
     * @return JsonRule
     */
    public function getChildRule(): JsonRule
    {
        return $this->childRule;
    }

    /**
     * @param JsonRule $childRule
     * @return TypedListRule
     */
    public function setChildRule(JsonRule $childRule): TypedListRule
    {
        $this->childRule = $childRule;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->childRule . self::LIST_TYPE_SUFFIX;
    }
}