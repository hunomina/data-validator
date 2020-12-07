<?php

namespace hunomina\DataValidator\Rule\Json;

use hunomina\DataValidator\Exception\Json\InvalidDataException;
use hunomina\DataValidator\Exception\Json\InvalidRuleException;
use hunomina\DataValidator\Rule\Json\Traits\EmptyCheckTrait;
use hunomina\DataValidator\Rule\Json\Traits\EnumCheckTrait;
use hunomina\DataValidator\Rule\Json\Traits\LengthCheckTrait;
use hunomina\DataValidator\Rule\Json\Traits\MaximumCheckTrait;
use hunomina\DataValidator\Rule\Json\Traits\MinimumCheckTrait;
use hunomina\DataValidator\Rule\Json\Traits\NullCheckTrait;

class TypedListRule extends JsonRule
{
    use NullCheckTrait;
    use LengthCheckTrait {
        setLength as private traitSetLength;
    }
    use MinimumCheckTrait {
        setMinimum as private traitSetMinimum;
    }
    use MaximumCheckTrait {
        setMaximum as private traitSetMaximum;
    }
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

        if (!$this->validateEmptiness($data)) {
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
    public function validateEmptiness(array $data): bool
    {
        if (($this->empty === false) && $this->minimum !== 0) {
            return count($data) !== 0;
        }

        return true;
    }

    /**
     * @param int|null $length
     * @throws InvalidRuleException
     */
    public function setLength(?int $length): void
    {
        try {
            $this->traitSetLength($length);
        } catch (InvalidRuleException $e) {
            throw new InvalidRuleException('`list-length` option must be greater or equal to 1', InvalidRuleException::INVALID_LIST_LENGTH_RULE, $e);
        }
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
     * @param float|null $maximum
     * @throws InvalidRuleException
     */
    public function setMaximum(?float $maximum): void
    {
        if ($maximum !== null && $maximum < 1) {
            throw new InvalidRuleException('`list-max` option must be greater or equal to 1', InvalidRuleException::INVALID_LIST_MAX_RULE);
        }
        $this->traitSetMaximum($maximum);
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
     * @param float|null $minimum
     * @throws InvalidRuleException
     */
    public function setMinimum(?float $minimum): void
    {
        if ($minimum !== null && $minimum < 0) {
            throw new InvalidRuleException('`list-min` option must be greater or equal to 0', InvalidRuleException::INVALID_LIST_MIN_RULE);
        }
        $this->traitSetMinimum($minimum);
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
     * @return string
     */
    public function getType(): string
    {
        return $this->childRule->getType() . self::LIST_TYPE_SUFFIX;
    }
}