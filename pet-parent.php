<?php
header("Content-Type: application/json");

// Allow only POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
    exit;
}

// Collect form data (matching your form data-role fields)
$name         = trim($_POST["pp_name"] ?? "");
$email        = trim($_POST["pp_email"] ?? "");
$location     = trim($_POST["location"] ?? "");
$pets         = trim($_POST["pets"] ?? "");
$petType      = trim($_POST["pp_type"] ?? "");
$breed        = trim($_POST["pp_breed"] ?? "");
$service      = trim($_POST["service"] ?? "");
$fromDate     = trim($_POST["from"] ?? "");
$toDate       = trim($_POST["to"] ?? "");
$walkDate     = trim($_POST["walkDate"] ?? "");
$walkTime     = trim($_POST["walkTime"] ?? "");

// Basic Validation
if ($name === "" || $email === "" || $location === "" || $pets === "" || $petType === "" || $service === "") {
    echo json_encode(["success" => false, "message" => "Please fill all required fields."]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["success" => false, "message" => "Invalid email address"]);
    exit;
}

// Build Email
$to = "shweta@pivotmkg.com";
$subject = "New Pet Parent Request – Service: $service";

$message = "
<html>
<body style='font-family: Arial;'>
<h2 style='color:#EA624E;'>New Pet Parent Lead</h2>

<p><strong>Name:</strong> $name</p>
<p><strong>Email:</strong> $email</p>
<p><strong>Locality:</strong> $location</p>

<h3 style='color:#FD9140;'>Pet Details</h3>
<p><strong>No. of Pets:</strong> $pets</p>
<p><strong>Pet Type:</strong> $petType</p>
<p><strong>Breed:</strong> " . ($breed ?: 'Not specified') . "</p>

<h3 style='color:#FD9140;'>Service Details</h3>
<p><strong>Service Needed:</strong> $service</p>";

if ($service === "walking") {
    $message .= "
    <p><strong>Walking Date:</strong> " . ($walkDate ?: 'Not provided') . "</p>
    <p><strong>Walking Time:</strong> " . ($walkTime ?: 'Not provided') . "</p>";
} else {
    $message .= "
    <p><strong>From Date:</strong> " . ($fromDate ?: 'Not provided') . "</p>
    <p><strong>To Date:</strong> " . ($toDate ?: 'Not provided') . "</p>";
}

$message .= "
<hr>
<p style='font-size:12px;color:#777;'>Lead from MyPetBnB – Pet Parent Form</p>
</body>
</html>
";

// Headers
$headers  = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=UTF-8\r\n";
$headers .= "From: MyPetBnB <no-reply@mypetbnb.com>\r\n";
$headers .= "Reply-To: $email\r\n";

// ⭐ ADD CC HERE
// $headers .= "Cc: rthomas@pivotmkg.com, aakash@pivotmkg.com\r\n";

// Send email
$sent = mail($to, $subject, $message, $headers);

if ($sent) {
    header("Location: thank-you.html");
    exit;
} else {
    header("Location: error.html");
    exit;
}
?>
