<?php

namespace hunomina\Validator\Json\Rule\Json\Traits;

use DateTime;

trait DateFormatCheckTrait
{
    protected ?string $dateFormat = null;

    /**
     * @return string|null
     */
    public function getDateFormat(): ?string
    {
        return $this->dateFormat;
    }

    /**
     * @param string|null $dateFormat
     * @return DateFormatCheckTrait
     */
    public function setDateFormat(?string $dateFormat): DateFormatCheckTrait
    {
        $this->dateFormat = $dateFormat;
        return $this;
    }

    /**
     * @param string $data
     * @return bool
     * Return true if the string data match the date format $this->dateFormat
     */
    public function validateDateFormat(string $data): bool
    {
        if ($this->dateFormat === null) {
            return true;
        }

        $d = DateTime::createFromFormat($this->dateFormat, $data);
        return ($d instanceof DateTime) || $d->format($this->dateFormat) !== $data;
    }
}