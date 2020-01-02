<?php

namespace hunomina\Validator\Json\Rule\Json;

use hunomina\Validator\Json\Exception\InvalidJsonRuleException;
use hunomina\Validator\Json\Rule\Json\Traits\DateFormatCheckTrait;
use hunomina\Validator\Json\Rule\Json\Traits\EmptyCheckTrait;
use hunomina\Validator\Json\Rule\Json\Traits\EnumCheckTrait;
use hunomina\Validator\Json\Rule\Json\Traits\LengthCheckTrait;
use hunomina\Validator\Json\Rule\Json\Traits\MaximumCheckTrait;
use hunomina\Validator\Json\Rule\Json\Traits\MinimumCheckTrait;
use hunomina\Validator\Json\Rule\Json\Traits\NullCheckTrait;
use hunomina\Validator\Json\Rule\Json\Traits\PatternCheckTrait;
use hunomina\Validator\Json\Rule\Rule;

abstract class JsonRuleFactory
{
    /**
     * @param string $type
     * @param array $options
     * @return Rule
     * @throws InvalidJsonRuleException
     */
    public static function create(string $type, array $options): Rule
    {
        $rule = self::getRuleObjectFromType($type);

        if (isset($options['optional'])) {
            if (!is_bool($options['optional'])) {
                throw new InvalidJsonRuleException('`optional` option must be a boolean', InvalidJsonRuleException::INVALID_OPTIONAL_RULE);
            }

            $rule->setOptional($options['optional']);
        }

        if ($rule instanceof TypedListRule) {
            self::setTypedListOptions($rule, $options);
            self::setScalarTypeOptions($rule->getChildRule(), $options);
        } else {
            self::setScalarTypeOptions($rule, $options);
        }

        return $rule;
    }

    /**
     * @param string $type
     * @return JsonRule
     * @throws InvalidJsonRuleException
     */
    private static function getRuleObjectFromType(string $type): JsonRule
    {
        switch ($type) {
            case JsonRule::STRING_TYPE:
                return new StringRule();
                break;
            case JsonRule::CHAR_TYPE:
                return new CharacterRule();
                break;
            case JsonRule::INTEGER_TYPE:
                return new IntegerRule();
                break;
            case JsonRule::FLOAT_TYPE:
                return new FloatRule();
                break;
            case JsonRule::NUMERIC_TYPE:
                return new NumericRule();
                break;
            case JsonRule::BOOLEAN_TYPE:
                return new BooleanRule();
                break;
        }

        if (preg_match('/([a-z]*)-list/', $type, $matches)) {
            return new TypedListRule(self::getRuleObjectFromType($matches[1]));
        }

        throw new InvalidJsonRuleException('Invalid rule type', InvalidJsonRuleException::INVALID_RULE_TYPE);
    }

    /**
     * @param JsonRule $rule
     * @param array $options
     * @throws InvalidJsonRuleException
     */
    private static function setScalarTypeOptions(JsonRule $rule, array $options): void
    {
        $checkRules = class_uses($rule);

        if (isset($options['null'])) {
            if (!isset($checkRules[NullCheckTrait::class])) {
                throw new InvalidJsonRuleException('`' . $rule->getType() . '` type can not be null', InvalidJsonRuleException::INVALID_NULL_RULE);
            }

            if (!is_bool($options['null'])) {
                throw new InvalidJsonRuleException('`null` option must be a boolean', InvalidJsonRuleException::INVALID_NULL_RULE);
            }

            /** @var NullCheckTrait $rule */
            $rule->setNullable($options['null']);
        }

        if (isset($options['length'])) {
            if (!isset($checkRules[LengthCheckTrait::class])) {
                throw new InvalidJsonRuleException('`' . $rule->getType() . '` type can not be length checked', InvalidJsonRuleException::INVALID_LENGTH_RULE);
            }

            if (!is_int($options['length'])) {
                throw new InvalidJsonRuleException('`length` option must be an integer', InvalidJsonRuleException::INVALID_LENGTH_RULE);
            }

            /** @var LengthCheckTrait $rule */
            $rule->setLength($options['length']);
        }

        if (isset($options['pattern'])) {
            if (!isset($checkRules[PatternCheckTrait::class])) {
                throw new InvalidJsonRuleException('`' . $rule->getType() . '` type can not be patterned checked', InvalidJsonRuleException::INVALID_PATTERN_RULE);
            }

            if (!is_string($options['pattern'])) {
                throw new InvalidJsonRuleException('`pattern` option must be a string', InvalidJsonRuleException::INVALID_PATTERN_RULE);
            }

            /** @var PatternCheckTrait $rule */
            $rule->setPattern($options['pattern']);
        }

        if (isset($options['min'])) {
            if (!isset($checkRules[MinimumCheckTrait::class])) {
                throw new InvalidJsonRuleException('`' . $rule->getType() . '` type can not be min checked', InvalidJsonRuleException::INVALID_MIN_RULE);
            }

            if (!is_int($options['min']) && !is_float($options['min'])) {
                throw new InvalidJsonRuleException('`min` option must be an integer or a floating number', InvalidJsonRuleException::INVALID_MIN_RULE);
            }

            /** @var MinimumCheckTrait $rule */
            $rule->setMinimum((float)$options['min']);
        }

        if (isset($options['max'])) {
            if (!isset($checkRules[MaximumCheckTrait::class])) {
                throw new InvalidJsonRuleException('`' . $rule->getType() . '` type can not be max checked', InvalidJsonRuleException::INVALID_MAX_RULE);
            }

            if (!is_int($options['max']) && !is_float($options['max'])) {
                throw new InvalidJsonRuleException('`max` option must be an integer or a floating number', InvalidJsonRuleException::INVALID_MAX_RULE);
            }

            /** @var MaximumCheckTrait $rule */
            $rule->setMaximum((float)$options['max']);
        }

        if (isset($options['enum'])) {
            if (!isset($checkRules[EnumCheckTrait::class])) {
                throw new InvalidJsonRuleException('`' . $rule->getType() . '` type can not be enum checked', InvalidJsonRuleException::INVALID_ENUM_RULE);
            }

            if (!is_array($options['enum'])) {
                throw new InvalidJsonRuleException('`enum` option must be an array', InvalidJsonRuleException::INVALID_ENUM_RULE);
            }

            /** @var EnumCheckTrait $rule */
            $rule->setEnum($options['enum']);
        }

        if (isset($options['date-format'])) {
            if (!isset($checkRules[DateFormatCheckTrait::class])) {
                throw new InvalidJsonRuleException('`' . $rule->getType() . '` type can not be date format checked', InvalidJsonRuleException::INVALID_DATE_FORMAT_RULE);
            }

            if (!is_string($options['date-format'])) {
                throw new InvalidJsonRuleException('`date-format` option must be a string', InvalidJsonRuleException::INVALID_DATE_FORMAT_RULE);
            }

            /** @var DateFormatCheckTrait $rule */
            $rule->setDateFormat($options['date-format']);
        }

        if (isset($options['empty'])) {
            if (!isset($checkRules[EmptyCheckTrait::class])) {
                throw new InvalidJsonRuleException('`' . $rule->getType() . '` type can not be empty checked', InvalidJsonRuleException::INVALID_EMPTY_RULE);
            }

            if (!is_bool($options['empty'])) {
                throw new InvalidJsonRuleException('`empty` option must be a boolean', InvalidJsonRuleException::INVALID_EMPTY_RULE);
            }

            /** @var EmptyCheckTrait $rule */
            $rule->setEmpty($options['empty']);
        }
    }

    /**
     * @param TypedListRule $rule
     * @param array $options
     * @throws InvalidJsonRuleException
     */
    private static function setTypedListOptions(TypedListRule $rule, array $options): void
    {
        if (isset($options['list-null'])) {
            if (!is_bool($options['list-null'])) {
                throw new InvalidJsonRuleException('`list-null` option must be a boolean', InvalidJsonRuleException::INVALID_LIST_NULL_RULE);
            }

            $rule->setNullable($options['list-null']);
        }

        if (isset($options['list-length'])) {
            if (!is_int($options['list-length'])) {
                throw new InvalidJsonRuleException('`list-length` option must be an integer', InvalidJsonRuleException::INVALID_LIST_LENGTH_RULE);
            }

            $rule->setLength($options['list-length']);
        }

        if (isset($options['list-min'])) {
            if (!is_int($options['list-min']) && !is_float($options['list-min'])) {
                throw new InvalidJsonRuleException('`list-min` option must be an integer or a floating number', InvalidJsonRuleException::INVALID_LIST_MIN_RULE);
            }

            $rule->setMinimum($options['list-min']);
        }

        if (isset($options['list-max'])) {
            if (!is_int($options['list-max']) && !is_float($options['list-max'])) {
                throw new InvalidJsonRuleException('`list-max` option must be an integer or a floating number', InvalidJsonRuleException::INVALID_LIST_MAX_RULE);
            }

            $rule->setMaximum($options['list-max']);
        }

        if (isset($options['list-empty'])) {
            if (!is_bool($options['empty'])) {
                throw new InvalidJsonRuleException('`list-empty` option must be a boolean', InvalidJsonRuleException::INVALID_LIST_EMPTY_RULE);
            }

            $rule->setEmpty($options['empty']);
        }
    }
}