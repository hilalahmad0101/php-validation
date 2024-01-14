# SuperValidator Documentation

The `SuperValidator` Package is a powerful PHP class designed to simplify and enhance the validation of form data in web applications. It provides a flexible and extensible framework for defining validation rules and generating meaningful error messages.

## Features

- **Extensible Rule System:** Easily define and extend validation rules for various types of data, such as strings, emails, passwords, dates, and more.

- **Customizable Error Messages:** Customize error messages for each validation rule, allowing you to provide clear and user-friendly feedback to your users.

- **Support for Common Validation Scenarios:** Includes built-in validation rules for common scenarios such as required fields, string length, email format, password strength, and more.

- **Rule Composition:** Compose complex validation scenarios by combining multiple rules for each field.

- **Data Validation:** Validate form data against a set of predefined rules, ensuring data integrity and security.

## Getting Started

### Installation

Install the `SuperValidator` package via [Composer](https://getcomposer.org/):

```bash
composer require hilalahmad/supervalidator
```


```bash
1. required
2. email
3. password
4. string
5. boolen
6. url
7. integer
8. number
9. date
10. min:length
11. max:length
12. image:1024px // depend on your size
13. Much more with custom validation message
```

```bash
<?php

require './vendor/autoload.php';

use Hilalahmad\Supervalidator\SuperValidator;


$customErrorMessages = [
    'email.required' => 'email field is required.',
    'email.email' => 'Invalid email address.',
    'password.required' => 'Password is required.',
    'password.confirmed:password_confirmation' => 'Password confirmation does not match.',
    'password.password' => 'Custom error message for the password.',
];
$validator = new SuperValidator($customErrorMessages);

$data = [
    'url' => ['required', 'date'],
    'email' => ['required', 'email'],
    'password' => ['required', 'confirmed:password_confirmation', 'password'],
];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Assuming you have the SuperValidator class loaded and instantiated

    if (!$validator->validate($data)) {
        $errors = $validator->getErrors();
    } else {
        // Validation successful, process the form data
        $name = $_POST['name'];
        $email = $_POST['email'];   
        $password = $_POST['password'];

        echo "Form submitted successfully!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sample Form</title>
</head>

<body>
<form action="" method="post">
    <label for="name">Name:</label>
    <input type="text" id="url" name="url">
    <?php
    if (isset($errors['url'])) {
        echo $errors['url'];
    }
    ?>
    <br>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email">
    <?php
    if (isset($errors['email'])) {
        echo $errors['email'];
    }
    ?>
    <br>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password">
    <?php
    if (isset($errors['password'])) {
        echo $errors['password'];
    }
    ?>
    <br>

    <label for="password_confirmation">Confirm Password:</label>
    <input type="password" id="password_confirmation" name="password_confirmation">

    <br>

    <input type="submit" value="Submit">
</form>

</body>

</html>
```