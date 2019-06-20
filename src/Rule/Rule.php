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
     */
    public function getType(): string;

    /**
     * @param string $type
     * @return Rule
     */
    public function setType(string $type): self;

    /**
     * @return bool
     */
    public function canBeNull(): bool;

    /**
     * @param bool $null
     * @return Rule
     */
    public function setNullable(bool $null): self;

    /**
     * @return bool
     */
    public function isOptional(): bool;

    /**
     * @param bool $isOptional
     * @return Rule
     */
    public function setOptional(bool $isOptional): self;

    /**
     * @return null|int
     * `null` if length does have to be checked
     */
    public function getLength(): ?int;

    /**
     * @param int $length
     * @return Rule
     * `null` if length does have to be checked
     */
    public function setLength(?int $length): self;

    /**
     * @param string|null $pattern
     * @return Rule
     * `null` if pattern does not have to be checked
     */
    public function setPattern(?string $pattern): self;

    /**
     * @return string|null
     * `null` if pattern does not have to be checked
     */
    public function getPattern(): ?string;

    /**
     * @return int|null
     */
    public function getMin(): ?int;

    /**
     * @param int|null $min
     * @return Rule
     */
    public function setMin(?int $min): self;

    /**
     * @return int|null
     */
    public function getMax(): ?int;

    /**
     * @param int|null $max
     * @return Rule
     */
    public function setMax(?int $max): self;

    /**
     * @return array
     */
    public function getEnum(): ?array;

    /**
     * @param array|null $enum
     * @return Rule
     */
    public function setEnum(?array $enum): self ;
}