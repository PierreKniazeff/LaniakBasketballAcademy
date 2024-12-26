<?php
$page_title = 'confirmation'; // Define the variable for menu.php
header('Content-Type: text/html; charset=UTF-8');
require_once __DIR__ . '/../views/common/header.php';
require_once __DIR__ . '/../views/common/menu.php';
require_once __DIR__ . '/../models/User.class.php';
require_once __DIR__ . '/../controllers/crudEn.php';
?>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registration Confirmation</title>

    <!-- CSS Styles -->
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #F5F5F5; /* Light gray background for the page */
        }
    </style>
</head>
<div>
<?php
// Initialize the CRUD object
$db = new CRUD();

// Check the value of the token and the received email
$token = $_REQUEST['token']; // Use $_REQUEST to retrieve the token
$email = $_REQUEST['email']; // Retrieve the email passed in the URL

// Check if the token and email are present in the URL
if (isset($token) && !empty($token) && isset($email) && !empty($email)) {
    // Confirm the user using the token
    $result = $db->confirmUserByToken($token, $email); // Also pass the email
    // Check the result
    if ($result['success']) {
        echo "<div class='success'>Congratulations! Your registration is confirmed.</div>";
        echo "<div class='success'>Your player profile has been successfully submitted to LaniakBasketballAcademy.</div>";
    } else {
        echo "<div class='error'>{$result['message']}</div>";
    }
} else {
    echo "<div class='error'>No valid token provided.</div>";
}

?>
</div>
<?php require_once __DIR__ . '/../views/common/footer.php'; ?>
