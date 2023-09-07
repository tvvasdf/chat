<?php

class Validator
{
    private string $field;
    private string $string;
    private string $error;
    private array $length;
    private array $options;
    private const OPTIONS_SEPARATOR = ':';

    public const LENGTH = 'length';
    public const TRIMMED = 'trim';
    public const CYRILLIC = 'cyrillic'. self::OPTIONS_SEPARATOR . 'true';
    public const NUMERIC = 'numeric'. self::OPTIONS_SEPARATOR . 'true';
    public const NON_CYRILLIC = 'cyrillic'. self::OPTIONS_SEPARATOR . 'false';
    public const NON_NUMERIC = 'numeric'. self::OPTIONS_SEPARATOR . 'false';

    public function __construct(string $string, string $field = 'Поле', array $options = [])
    {
        $this->string = $string;
        $this->field = $field;
        $this->setLength(4, 16);
        if (!$options) {
            $this->options = [
                self::TRIMMED,
                self::LENGTH,
                self::NON_CYRILLIC,
                self::NON_NUMERIC,
            ];
        } else {
            $this->options = $options;
        }
    }

    public function validate(): string
    {
        foreach ($this->options as $func) {
            $option = explode(self::OPTIONS_SEPARATOR, $func)[1] ? : '';
            $func = explode(self::OPTIONS_SEPARATOR, $func)[0];
            if (!$this->$func($option)) {
                return '';
            }
        }
        return $this->string;
    }

    public function setLength(int $min, int $max): void
    {
        $this->length['min'] = $min;
        $this->length['max'] = $max;
    }

    public function getError(): string
    {
        return $this->error;
    }

    private function trim(): bool
    {
        $this->string = trim($this->string);
        return (bool) $this->string;
    }

    private function length(): bool
    {
        $min = $this->length['min'] ?? 0;
        $max = $this->length['max'] ?? 0;
        if (strlen($this->string) > $max) {
            $this->error = $this->field . ' может содержать максимум ' . $max . ' символов';
        }
        if (strlen($this->string) < $min) {
            $this->error = $this->field . ' может содержать минимум ' . $min . ' символов';
        }
        return strlen($this->string) <= $max && strlen($this->string) >= $min;
    }

    private function cyrillic(bool $cyrillic = false): bool
    {
        if ($cyrillic) {
            $this->error = $this->field . ' может содержать только символы кириллицы';
            return is_numeric($this->string);
        } else {
            $this->error = $this->field . ' не может содержать символы кириллицы';
            return !is_numeric($this->string);
        }
    }

    private function numeric(bool $numeric = false): bool
    {
        if ($numeric) {
            $this->error = $this->field . ' может содержать только цифры';
            return is_numeric($this->string);
        } else {
            $this->error = $this->field . ' не может содержать только цифры';
            return !is_numeric($this->string);
        }
    }
}