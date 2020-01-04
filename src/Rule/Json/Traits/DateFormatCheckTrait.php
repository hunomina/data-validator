<?php

namespace hunomina\Validator\Json\Rule\Json\Traits;

use DateTime;

trait DateFormatCheckTrait
{
    protected ?string $dateFormat = null;

    /**
     * @return string|null
     * @codeCoverageIgnore
     */
    public function getDateFormat(): ?string
    {
        return $this->dateFormat;
    }

    /**
     * @param string|null $dateFormat
     */
    public function setDateFormat(?string $dateFormat): void
    {
        $this->dateFormat = $dateFormat;
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
        return ($d instanceof DateTime) && $d->format($this->dateFormat) === $data;
    }
}