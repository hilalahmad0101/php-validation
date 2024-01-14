<?php

require './vendor/autoload.php';

use Hilalahmad\Supervalidator\SuperValidator;


$customErrorMessages = [
    // 'url.required' => 'url is required.aaaa',
    // 'url.string' => 'url must be a string.',
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