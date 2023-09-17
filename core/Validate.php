<?php

namespace Vendon\core;

class Validate
{
    const RULE_REQUIRED = 'required';
    private array $errors = [];

    /**
     * @param $payload
     * @param $rules
     * @return bool
     */
    //vvv Validate passed payload to rules to check if data corresponds to rules
    public function validate($payload, $rules): bool
    {
        foreach ($payload as $attribute => $value) {
            if (isset($rules[$attribute])) {
                $attributeRules = $rules[$attribute];

                foreach ($attributeRules as $rule) {
                    $ruleName = $rule;

                    if ($ruleName === self::RULE_REQUIRED && empty($value)) {
                        $this->errors[] = [$attribute => 'This field is required'];
                    }
                }
            }
        }

        return empty($this->errors);
    }

    /**
     * @return array
     */
    //vvv Getter function for errors
    public function getErrors(): array
    {
        return $this->errors;
    }
}