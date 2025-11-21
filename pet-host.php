<?php
header("Content-Type: application/json");

// Allow only POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Invalid request"]);
    exit;
}

// Collect form data (matching your host form fields)
$name            = trim($_POST["host_name"] ?? "");
$email           = trim($_POST["host_email"] ?? "");
$location        = trim($_POST["location"] ?? "");
$hostExperience  = trim($_POST["host_experience"] ?? "");
$hostRate        = trim($_POST["host_rate"] ?? "");
$service         = trim($_POST["service"] ?? "");
$hostPetTypes    = isset($_POST["host_pet_types"]) ? implode(", ", $_POST["host_pet_types"]) : "";

// Validation
if ($name === "" || $email === "" || $location === "" || $hostExperience === "" || $hostRate === "" || $service === "") {
    echo json_encode(["success" => false, "message" => "Please fill all required fields."]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(["success" => false, "message" => "Invalid email address"]);
    exit;
}

// Email destination
$to = "shweta@pivotmkg.com";
$subject = "New Pet Host Registration – $name";

// Build message
$message = "
<html>
<body style='font-family: Arial;'>
<h2 style='color:#EA624E;'>New Pet Host Registration</h2>

<h3 style='color:#FD9140;'>Personal Details</h3>
<p><strong>Name:</strong> $name</p>
<p><strong>Email:</strong> $email</p>
<p><strong>Locality:</strong> $location</p>

<h3 style='color:#FD9140;'>Hosting Information</h3>
<p><strong>Experience:</strong> $hostExperience</p>
<p><strong>Daily Rate (₹):</strong> $hostRate</p>
<p><strong>Primary Service Offered:</strong> $service</p>

<h3 style='color:#FD9140;'>Pet Hosting Capability</h3>
<p><strong>Pet Types You Host:</strong> " . ($hostPetTypes ?: "Not specified") . "</p>

<hr>
<p style='font-size:12px;color:#777;'>Lead from MyPetBnB – Pet Host Form</p>
</body>
</html>
";

// Headers
$headers  = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=UTF-8\r\n";
$headers .= "From: MyPetBnB <no-reply@mypetbnb.com>\r\n";
$headers .= "Reply-To: $email\r\n";

// ⭐ CC Section (same as pet-parent.php)
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
