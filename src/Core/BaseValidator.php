<?php

namespace PospalHelper\Core;

use PospalHelper\Core\Exception\UnexpectedValidatorException;
use PospalHelper\Core\Validator;

abstract class BaseValidator
{
    /** @var array<string, array<string>>  */
    protected array $rules = [];

    /** @var array<string, array<string, string>>  */
    protected array $message = [];

    /** @var array<string>  */
    private array $errors = [];

    /** @var array<string, mixed>  */
    private array $data = [];

    /** @var array<string>  */
    private array $except = [];

    /** @var array<string, string> */
    private static array $map = [
//        'require' => Validator\RequireValidator::class,
        'mutual' => Validator\MutualValidator::class,
        'integer' => Validator\FloatValidator::class,
        'float' => Validator\FloatValidator::class,
        'array' => Validator\ArrayValidator::class,
        'decimal' => Validator\DecimalValidator::class,
        'string' => Validator\StringValidator::class,
    ];

    /** @var array<string, Validator\ValidatorInterface> */
    private static array $container = [];

    /**
     * @param array<string, mixed>|null $data
     * @param array<string, mixed> $return
     * @return bool
     */
    public function check(?array &$return = null, ?array $data = null): bool
    {
        /** @var array<string, mixed> $data */$data = $data ?? $this->data;
        /** @var array<string, array<string>> $rules */ $rules = $this->array_except($this->rules, $this->except);

        $this->errors = [];
        $return = [];

        foreach ($rules as $name => $rule) {
            if (!is_array($rule)) {
                continue;
            }
            $require = array_search('require', $rule);
            if ($require !== false) unset($rule[$require]);
            if (!isset($data[$name])) {
                if ($require) {
                    $this->errors[] = $name . 'could not be empty';
                }
                continue;
            }

            foreach ($rule as $item) {
                [$ii] = $args = explode('|', $item);
                array_shift($args);
                $validator = self::getValidator($ii);
                $result = $validator->validate($name, $data, $args);
                if ($result) {
                    $return[$name] = $data[$name];
                    continue;
                }

                $this->errors[] = $this->message[$name][$item] ?? $validator->message($name);
            }
        }

        return empty($this->errors);
    }

    public function add(string $name, array $rule, array $message): BaseValidator
    {
        $this->rules[$name] = $rule;
        $this->message[$name] = $message;
        return $this;
    }

    public function except(string $name): BaseValidator
    {
        $this->except[] = $name;
        return $this;
    }

    public function data(array $data): BaseValidator
    {
        $this->data = $data;
        return $this;
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        static $current = 0;
        return $this->errors[$current = ($current++) % count($this->errors)];
    }

    /**
     * @return array<string>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Remove value by keys from another value of array
     *
     * @param array<string|int, mixed> $row
     * @param array<string|int> $except
     * @return array
     */
    private function array_except(array $row, array $except): array
    {
        foreach ($except as $key) {
            if (isset($row[$key])) unset($row[$key]);
        }

        return $row;
    }

    /**
     * @param string $rule
     * @throws UnexpectedValidatorException
     * @return Validator\ValidatorInterface
     */
    private static function getValidator(string $rule): Validator\ValidatorInterface
    {
        if (!isset(self::$map[$rule])) throw new UnexpectedValidatorException("Could not validate by $rule");
        return self::$container[$rule] ?? self::$container[$rule] = new self::$map[$rule];
    }
}