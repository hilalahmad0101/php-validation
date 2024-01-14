<?php

namespace Hilalahmad\Supervalidator;

use DateTime;

class SuperValidator
{
    private $errors = [];
    private $customErrorMessages = [];

    public function __construct(array $customErrorMessages = [])
    {
        $this->customErrorMessages = $customErrorMessages;
    }

    public function validate(array $data)
    {
        foreach ($data as $field => $rules) {
            foreach ($rules as $rule) {
                // Split the rule into the rule name and parameters (if any)
                [$ruleName, $parameters] = $this->parseRule($rule);

                // Call the corresponding validation method
                $method = 'validate' . ucfirst($ruleName);
                $isValid = $this->$method($field, $parameters);

                // If validation fails, add an error
                if (!$isValid) {
                    $this->addError($field, $this->getErrorMessage($field, $ruleName, $parameters));
                    // Break out of the inner loop on the first validation failure for this field
                    break;
                }
            }
        }

        return empty($this->errors);
    }

    public function getErrors()
    {
        return $this->errors;
    }
    private function addError($field, $message)
    {
        $this->errors[$field] = $message;
    }

    private function parseRule($rule)
    {
        // Split the rule into the rule name and parameters (if any)
        $parts = explode(':', $rule, 2);
        $ruleName = $parts[0];
        $parameters = isset($parts[1]) ? explode(',', $parts[1]) : [];

        return [$ruleName, $parameters];
    }

    private function validateRequired($field)
    {
        return isset($_POST[$field]) && !empty($_POST[$field]);
    }

    private function validateString($field, $parameters)
    {
        $value = $_POST[$field] ?? null;
        return is_string($value);
    }

    private function validateMax($field, $parameters)
    {
        $value = $_POST[$field] ?? null;
        $max = $parameters[0] ?? null;

        return strlen($value) <= $max;
    }


    private function validateConfirmed($field, $parameters)
    {
        $confirmationField = $parameters[0] ?? null;
        $value = $_POST[$field] ?? null;
        $confirmationValue = $_POST[$confirmationField] ?? null;

        return $value === $confirmationValue;
    }

    private function validateEmail($field, $parameters)
    {
        $value = $_POST[$field] ?? null;
        return filter_var($value, FILTER_VALIDATE_EMAIL) !== false;
    }

    private function validatePassword($field, $parameters)
    {
        $value = $_POST[$field] ?? null;

        // Check for at least one uppercase letter
        $uppercase = preg_match('/[A-Z]/', $value);

        // Check for at least one lowercase letter
        $lowercase = preg_match('/[a-z]/', $value);

        // Check for at least one digit
        $number = preg_match('/\d/', $value);

        // Check for at least one special character
        $symbol = preg_match('/[^A-Za-z0-9]/', $value);

        // Check if all conditions are met
        return $uppercase && $lowercase && $number && $symbol;
    }


    private function validateMin($field, $parameters)
    {
        $value = $_POST[$field] ?? null;
        $minLength = $parameters[0] ?? null;

        return strlen($value) >= $minLength;
    }

    private function validateNumeric($field, $parameters)
    {
        $value = $_POST[$field] ?? null;

        return is_numeric($value);
    }

    private function validateInteger($field, $parameters)
    {
        $value = $_POST[$field] ?? null;

        return filter_var($value, FILTER_VALIDATE_INT) !== false;
    }

    private function validateBoolean($field, $parameters)
    {
        $value = $_POST[$field] ?? null;

        // You can customize this logic based on your specific criteria for a boolean value
        return filter_var($value, FILTER_VALIDATE_BOOLEAN) !== false;
    }

    private function validateUrl($field, $parameters)
    {
        $value = $_POST[$field] ?? null;

        return filter_var($value, FILTER_VALIDATE_URL) !== false;
    }

    private function validateIp($field, $parameters)
    {
        $value = $_POST[$field] ?? null;

        // Use FILTER_VALIDATE_IP with options to allow both IPv4 and IPv6
        return filter_var($value, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6) !== false;
    }

    private function validateDate($field, $parameters)
    {
        $value = $_POST[$field] ?? null;

        // Assuming a date format like YYYY-MM-DD for simplicity
        $dateFormat = 'Y-m-d';

        $date = DateTime::createFromFormat($dateFormat, $value);

        return $date && $date->format($dateFormat) === $value;
    }

    private function validateDateFormat($field, $parameters)
    {
        $value = $_POST[$field] ?? null;
        $format = $parameters[0] ?? null;

        if (!$format) {
            // If no date format is provided, consider it a validation failure
            return false;
        }

        $date = DateTime::createFromFormat($format, $value);

        return $date && $date->format($format) === $value;
    }

    private function getErrorMessage($field, $rule, $parameters)
    {
        $customErrorMessageKey = $field . '.' . $rule;
        if (isset($this->customErrorMessages[$customErrorMessageKey])) {
            return $this->customErrorMessages[$customErrorMessageKey];
        }
        switch ($rule) {
            case 'required':
                return ucfirst($field) . ' is required.';
            case 'string':
                return ucfirst($field) . ' must be a string.';
            case 'max':
                return ucfirst($field) . ' must be at most ' . $parameters[0] . ' characters long.';
            case 'confirmed':
                return ucfirst($field) . ' confirmation does not match.';
            case 'email':
                return ucfirst($field) . ' must be a valid email address.';
            case 'min':
                return ucfirst($field) . ' must be at least ' . $parameters[0] . ' characters long.';
            case 'password':
                return ucfirst($field) . ' must contain at least one uppercase letter, one lowercase letter, one digit, and one special character.';
            case 'numeric':
                return ucfirst($field) . ' must be a numeric value.';
            case 'integer':
                return ucfirst($field) . ' must be an integer.';
            case 'boolean':
                return ucfirst($field) . ' must be a boolean value.';
            case 'url':
                return ucfirst($field) . ' must be a valid URL.';
            case 'ip':
                return ucfirst($field) . ' must be a valid IP address.';
            case 'date':
                return ucfirst($field) . ' must be a valid date.';
            case 'date_format':
                $format = $parameters[0] ?? 'Y-m-d'; // Default format if not provided
                return ucfirst($field) . ' must be a valid date in the format ' . $format . '.';
            default:
                return ucfirst($field) . ' validation failed for rule ' . $rule . '.';
        }
    }
}
