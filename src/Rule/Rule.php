<?php

namespace hunomina\Validator\Json\Rule;

interface Rule
{
    /**
     * @param $data
     * @return bool
     * Validate a data based on his type and length (if possible)
     */
    public function validate($data): bool;

    /**
     * @return string
     * The data type
     */
    public function getType(): string;

    /**
     * @param string $type
     * @return Rule
     */
    public function setType(string $type): self;

    /**
     * @return bool
     * Can the data equals `null`
     */
    public function canBeNull(): bool;

    /**
     * @param bool $null
     * @return Rule
     */
    public function setNullable(bool $null): self;

    /**
     * @return bool
     * Is the data optional <=> is it mandatory in the parent schema
     */
    public function isOptional(): bool;

    /**
     * @param bool $isOptional
     * @return Rule
     */
    public function setOptional(bool $isOptional): self;

    /**
     * @return null|int
     * `null` if does not have to be checked
     * Length of the data (string, lists...)
     */
    public function getLength(): ?int;

    /**
     * @param int $length
     * @return Rule
     */
    public function setLength(?int $length): self;

    /**
     * @return string|null
     * `null` if pattern does not have to be checked
     * Regex that the data must match
     */
    public function getPattern(): ?string;

    /**
     * @param string|null $pattern
     * @return Rule
     */
    public function setPattern(?string $pattern): self;

    /**
     * @return int|null
     * `null` if minimum value does not have to be checked
     * Minimum value of the data (list length, number value...)
     */
    public function getMin(): ?int;

    /**
     * @param int|null $min
     * @return Rule
     */
    public function setMin(?int $min): self;

    /**
     * @return int|null
     * `null` if does not have to be checked
     * Maximum value of the data (list length, number value...)
     */
    public function getMax(): ?int;

    /**
     * @param int|null $max
     * @return Rule
     */
    public function setMax(?int $max): self;

    /**
     * @return array
     * `null` if does not have to be checked
     * List of value available for the tested data
     */
    public function getEnum(): ?array;

    /**
     * @param array|null $enum
     * @return Rule
     */
    public function setEnum(?array $enum): self;

    /**
     * @return string|null
     * `null` if no error
     * Error message if the data does not match the rule
     */
    public function getError(): ?string;

    /**
     * @param string|null $error
     * @return JsonRule
     */
    public function setError(?string $error): self;

    /**
     * @return string|null
     * `null` if does not have to be checked
     * Date format to test the data with
     */
    public function getDateFormat(): ?string;

    /**
     * @param string|null $dateFormat
     * @return Rule
     */
    public function setDateFormat(?string $dateFormat): Rule;
}