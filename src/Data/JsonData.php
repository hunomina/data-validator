<?php

namespace hunomina\Validator\Json\Data;

use ArrayAccess;
use hunomina\Validator\Json\Exception\InvalidDataException;

class JsonData implements DataType, ArrayAccess
{
    /**
     * @var array $data
     */
    private $data;

    /**
     * @return array
     */
    public function getData(): ?array
    {
        return $this->data;
    }

    /**
     * @param string $data
     * @return DataType
     * @throws InvalidDataException
     */
    public function setData($data): DataType
    {
        if ($data === null) {
            $this->data = null;
        } else {
            $this->setDataFromArray($this->format($data));
        }
        return $this;
    }

    /**
     * @param array $data
     * @return DataType
     */
    public function setDataFromArray(array $data): DataType
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @param $data
     * @return mixed
     * @throws InvalidDataException
     */
    public function format($data): array
    {
        if (!is_string($data)) {
            throw new InvalidDataException('Can only parse string to json data');
        }

        $jsonData = json_decode($data, true);

        if (!is_array($jsonData)) {
            throw new InvalidDataException('Invalid string to parse to json data');
        }

        return $jsonData;
    }

    /**
     * Whether a offset exists
     * @link https://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset <p>
     * An offset to check for.
     * </p>
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     * @codeCoverageIgnore
     */
    public function offsetExists($offset): bool
    {
        return isset($this->data[$offset]);
    }

    /**
     * Offset to retrieve
     * @link https://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset <p>
     * The offset to retrieve.
     * </p>
     * @return mixed Can return all value types.
     * @since 5.0.0
     * @codeCoverageIgnore
     */
    public function offsetGet($offset)
    {
        if ($this->offsetExists($offset)) {
            return $this->data[$offset];
        }
        return null;
    }

    /**
     * Offset to set
     * @link https://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset <p>
     * The offset to assign the value to.
     * </p>
     * @param mixed $value <p>
     * The value to set.
     * </p>
     * @return void
     * @since 5.0.0
     * @codeCoverageIgnore
     */
    public function offsetSet($offset, $value): void
    {
        $this->data[$offset] = $value;
    }

    /**
     * Offset to unset
     * @link https://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset <p>
     * The offset to unset.
     * </p>
     * @return void
     * @since 5.0.0
     * @codeCoverageIgnore
     */
    public function offsetUnset($offset): void
    {
        unset($this->data[$offset]);
    }
}