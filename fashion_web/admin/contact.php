<?php
include 'components/connect.php'; // Adjust the path as needed

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8');
    $email   = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $subject = htmlspecialchars($_POST['subject'], ENT_QUOTES, 'UTF-8');
    $message = htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8');
    $created_at = date("Y-m-d H:i:s");

    try {
        // Insert into database
        $insert = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message, created_at) VALUES (?, ?, ?, ?, ?)");
        $insert->execute([$name, $email, $subject, $message, $created_at]);

        // Redirect to thank you page
        header("Location: contact_thankyou.html");
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .container {
            display: flex;
            height: 100vh;
            width: 100vw;
        }
        .map {
            flex: 1;
        }
        .form-container {
            flex: 1;
            padding: 20px;
            background-color: #f8f9fa;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }
        input, textarea {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            background-color: #007bff;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }
        h2 {
            text-align: center;
        }
        .cont {
            display: flex;
            flex-direction: column;
            align-items: center;
            width: 100%;
        }
        .address {
            text-align: center;
            padding: 20px;
        }
        .box-container {
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        .box {
            display: flex;
            align-items: center;
            gap: 10px;
            border: 1px solid #ddd;
            padding: 10px;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<?php include 'components/contact_header.php';?>

<div class="container">
    <div class="map">
        <iframe 
            src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d113827.62605834556!2d78.9628801!3d20.593684!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sen!2sin" 
            width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy">
        </iframe>
    </div>
    
    <div class="form-container">
        <h2>Contact Us</h2>
        <form action="" method="POST">
            <label>Your Name *</label>
            <input type="text" name="name" required>
            
            <label>Your Email *</label>
            <input type="email" name="email" required>
            
            <label>Subject *</label>
            <input type="text" name="subject" required>
            
            <label>Message *</label>
            <textarea name="message" rows="4" required></textarea>
            
            <button type="submit">Send Message</button>
        </form>
    </div>
</div>

<div class="address">
    <div class="heading">
        <h1>Our Contact Details</h1>
        <p>Finolex Academy Of Management and Technology, Ratnagiri</p>
        <img src="image/separator.png" alt="Separator">
    </div>
    <div class="box-container">
        <div class="box">
            <i class="bx bxs-map-alt"></i>
            <div>
                <h4>Address</h4>
                <p>Finolex Academy Of Management and Technology, Ratnagiri</p>
            </div>
        </div>
        <div class="box">
            <i class="bx bxs-phone-incoming"></i>
            <div>
                <h4>Phone Number</h4>
                <p>02352-299361</p>
            </div>
        </div>
        <div class="box">
            <i class="bx bxs-envelope"></i>
            <div>
                <h4>Email</h4>
                <p>famt@gmail.com</p>
            </div>
        </div>
    </div>
</div>

</body>
</html>
