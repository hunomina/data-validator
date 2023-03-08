<?php

namespace hunomina\DataValidator\Rule\Json\Factory;

use hunomina\DataValidator\Exception\Json\InvalidRuleException;
use hunomina\DataValidator\Rule\Json\BooleanRule;
use hunomina\DataValidator\Rule\Json\CharacterRule;
use hunomina\DataValidator\Rule\Json\FloatRule;
use hunomina\DataValidator\Rule\Json\IntegerRule;
use hunomina\DataValidator\Rule\Json\JsonRule;
use hunomina\DataValidator\Rule\Json\NumericRule;
use hunomina\DataValidator\Rule\Json\StringRule;
use hunomina\DataValidator\Rule\Json\Check\DateFormatCheckTrait;
use hunomina\DataValidator\Rule\Json\Check\EmptyCheckTrait;
use hunomina\DataValidator\Rule\Json\Check\EnumCheckTrait;
use hunomina\DataValidator\Rule\Json\Check\LengthCheckTrait;
use hunomina\DataValidator\Rule\Json\Check\MaximumCheckTrait;
use hunomina\DataValidator\Rule\Json\Check\MinimumCheckTrait;
use hunomina\DataValidator\Rule\Json\Check\NullCheckTrait;
use hunomina\DataValidator\Rule\Json\Check\PatternCheckTrait;
use hunomina\DataValidator\Rule\Json\TypedListRule;

abstract class JsonRuleFactory
{
    /**
     * @param string $type
     * @param array $options
     * @return JsonRule
     * @throws InvalidRuleException
     */
    public static function create(string $type, array $options): JsonRule
    {
        try {
            $rule = self::getRuleObjectFromType($type);
        } catch (InvalidRuleException) {
            throw new InvalidRuleException('Invalid rule type : `' . $type . '`', InvalidRuleException::INVALID_RULE_TYPE);
        }

        if (isset($options['optional'])) {
            if (!is_bool($options['optional'])) {
                throw new InvalidRuleException('`optional` option must be a boolean', InvalidRuleException::INVALID_OPTIONAL_RULE);
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
     * @throws InvalidRuleException
     */
    private static function getRuleObjectFromType(string $type): JsonRule
    {
        switch ($type) {
            case JsonRule::STRING_TYPE:
                return new StringRule();
            case JsonRule::CHAR_TYPE:
                return new CharacterRule();
            case JsonRule::INTEGER_TYPE:
                return new IntegerRule();
            case JsonRule::FLOAT_TYPE:
                return new FloatRule();
            case JsonRule::NUMERIC_TYPE:
                return new NumericRule();
            case JsonRule::BOOLEAN_TYPE:
                return new BooleanRule();
        }

        if (preg_match('/([a-z]+)-list/', $type, $matches)) {
            return new TypedListRule(self::getRuleObjectFromType($matches[1]));
        }

        throw new InvalidRuleException('Invalid rule type', InvalidRuleException::INVALID_RULE_TYPE);
    }

    /**
     * @param JsonRule $rule
     * @param array $options
     * @throws InvalidRuleException
     */
    private static function setScalarTypeOptions(JsonRule $rule, array $options): void
    {
        if (count(preg_grep('/^list-/', array_keys($options))) > 0) {
            throw new InvalidRuleException('`' . $rule->getType() . '` type does not support `list-` options', InvalidRuleException::INVALID_LIST_RULE_FOR_SCALAR_TYPE);
        }

        $checkRules = class_uses($rule);

        if (isset($options['null'])) {
            if (!isset($checkRules[NullCheckTrait::class])) {
                throw new InvalidRuleException('`' . $rule->getType() . '` type can not be null checked', InvalidRuleException::INVALID_NULL_RULE);
            }

            if (!is_bool($options['null'])) {
                throw new InvalidRuleException('`null` option must be a boolean', InvalidRuleException::INVALID_NULL_RULE);
            }

            /** @var NullCheckTrait $rule */
            $rule->setNullable($options['null']);
        }

        if (isset($options['length'])) {
            if (!isset($checkRules[LengthCheckTrait::class])) {
                throw new InvalidRuleException('`' . $rule->getType() . '` type can not be length checked', InvalidRuleException::INVALID_LENGTH_RULE);
            }

            if (!is_int($options['length'])) {
                throw new InvalidRuleException('`length` option must be an integer', InvalidRuleException::INVALID_LENGTH_RULE);
            }

            /** @var LengthCheckTrait $rule */
            $rule->setLength($options['length']);
        }

        if (isset($options['pattern'])) {
            if (!isset($checkRules[PatternCheckTrait::class])) {
                throw new InvalidRuleException('`' . $rule->getType() . '` type can not be patterned checked', InvalidRuleException::INVALID_PATTERN_RULE);
            }

            if (!is_string($options['pattern'])) {
                throw new InvalidRuleException('`pattern` option must be a string', InvalidRuleException::INVALID_PATTERN_RULE);
            }

            /** @var PatternCheckTrait $rule */
            $rule->setPattern($options['pattern']);
        }

        if (isset($options['min'])) {
            if (!isset($checkRules[MinimumCheckTrait::class])) {
                throw new InvalidRuleException('`' . $rule->getType() . '` type can not be min checked', InvalidRuleException::INVALID_MIN_RULE);
            }

            if (!is_int($options['min']) && !is_float($options['min'])) {
                throw new InvalidRuleException('`min` option must be an integer or a floating number', InvalidRuleException::INVALID_MIN_RULE);
            }

            /** @var MinimumCheckTrait $rule */
            $rule->setMinimum((float)$options['min']);
        }

        if (isset($options['max'])) {
            if (!isset($checkRules[MaximumCheckTrait::class])) {
                throw new InvalidRuleException('`' . $rule->getType() . '` type can not be max checked', InvalidRuleException::INVALID_MAX_RULE);
            }

            if (!is_int($options['max']) && !is_float($options['max'])) {
                throw new InvalidRuleException('`max` option must be an integer or a floating number', InvalidRuleException::INVALID_MAX_RULE);
            }

            /** @var MaximumCheckTrait $rule */
            $rule->setMaximum((float)$options['max']);
        }

        if (isset($options['enum'])) {
            if (!isset($checkRules[EnumCheckTrait::class])) {
                throw new InvalidRuleException('`' . $rule->getType() . '` type can not be enum checked', InvalidRuleException::INVALID_ENUM_RULE);
            }

            if (!is_array($options['enum'])) {
                throw new InvalidRuleException('`enum` option must be an array', InvalidRuleException::INVALID_ENUM_RULE);
            }

            /** @var EnumCheckTrait $rule */
            $rule->setEnum($options['enum']);
        }

        if (isset($options['date-format'])) {
            if (!isset($checkRules[DateFormatCheckTrait::class])) {
                throw new InvalidRuleException('`' . $rule->getType() . '` type can not be date format checked', InvalidRuleException::INVALID_DATE_FORMAT_RULE);
            }

            if (!is_string($options['date-format'])) {
                throw new InvalidRuleException('`date-format` option must be a string', InvalidRuleException::INVALID_DATE_FORMAT_RULE);
            }

            /** @var DateFormatCheckTrait $rule */
            $rule->setDateFormat($options['date-format']);
        }

        if (isset($options['empty'])) {
            if (!isset($checkRules[EmptyCheckTrait::class])) {
                throw new InvalidRuleException('`' . $rule->getType() . '` type can not be empty checked', InvalidRuleException::INVALID_EMPTY_RULE);
            }

            if (!is_bool($options['empty'])) {
                throw new InvalidRuleException('`empty` option must be a boolean', InvalidRuleException::INVALID_EMPTY_RULE);
            }

            /** @var EmptyCheckTrait $rule */
            $rule->setEmpty($options['empty']);
        }
    }

    /**
     * @param TypedListRule $rule
     * @param array $options
     * @throws InvalidRuleException
     * Options are unset once applied to be able to create the child rule from these options
     */
    private static function setTypedListOptions(TypedListRule $rule, array &$options): void
    {
        if (isset($options['list-null'])) {
            if (!is_bool($options['list-null'])) {
                throw new InvalidRuleException('`list-null` option must be a boolean', InvalidRuleException::INVALID_LIST_NULL_RULE);
            }

            $rule->setNullable($options['list-null']);
            unset($options['list-null']);
        }

        if (isset($options['list-length'])) {
            if (!is_int($options['list-length'])) {
                throw new InvalidRuleException('`list-length` option must be an integer', InvalidRuleException::INVALID_LIST_LENGTH_RULE);
            }

            $rule->setLength($options['list-length']);
            unset($options['list-length']);
        }

        if (isset($options['list-min'])) {
            if (!is_int($options['list-min']) && !is_float($options['list-min'])) {
                throw new InvalidRuleException('`list-min` option must be an integer or a floating number', InvalidRuleException::INVALID_LIST_MIN_RULE);
            }

            $rule->setMinimum($options['list-min']);
            unset($options['list-min']);
        }

        if (isset($options['list-max'])) {
            if (!is_int($options['list-max']) && !is_float($options['list-max'])) {
                throw new InvalidRuleException('`list-max` option must be an integer or a floating number', InvalidRuleException::INVALID_LIST_MAX_RULE);
            }

            $rule->setMaximum($options['list-max']);
            unset($options['list-max']);
        }

        if (isset($options['list-empty'])) {
            if (!is_bool($options['list-empty'])) {
                throw new InvalidRuleException('`list-empty` option must be a boolean', InvalidRuleException::INVALID_LIST_EMPTY_RULE);
            }

            $rule->setEmpty($options['list-empty']);
            unset($options['list-empty']);
        }
    }
}