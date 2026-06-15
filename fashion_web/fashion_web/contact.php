<?php
include 'components/connect.php';

if (isset($_COOKIE['user_id'])) {
    $user_id = $_COOKIE['user_id'];
} else {
    $user_id = '';
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_contact'])) {
    $name    = htmlspecialchars(trim($_POST['name']));
    $email   = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));
    $created_at = date("Y-m-d H:i:s");

    if (!empty($name) && !empty($email) && !empty($subject) && !empty($message)) {
        $insert = $conn->prepare("INSERT INTO contact_messages (name, email, subject, message, created_at) VALUES (?, ?, ?, ?, ?)");
        $insert->execute([$name, $email, $subject, $message, $created_at]);
        $success_msg[] = 'Your message has been sent! We will get back to you within 24 hours.';
    } else {
        $error_msg[] = 'Please fill in all fields before submitting.';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Contact Zari & Co. — reach out for any queries, feedback, or collaborations. We're here to help you.">
    <title>Contact Us | Zari & Co.</title>
    <link rel="icon" type="image/png" href="image/logo (1).png">
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" type="text/css" href="css/user_style.css?v=<?php echo time(); ?>">
    <style>
        .contact-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 80vh;
            gap: 0;
        }
        .contact-map iframe {
            width: 100%;
            height: 100%;
            min-height: 500px;
            border: 0;
            display: block;
        }
        .contact-form-wrap {
            background: #fff;
            padding: 4rem 3rem;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        .contact-form-wrap h2 {
            font-size: 2rem;
            color: var(--main-color);
            margin-bottom: 0.5rem;
            text-transform: capitalize;
        }
        .contact-form-wrap p.subtitle {
            color: #666;
            margin-bottom: 2rem;
            font-size: 1rem;
        }
        .contact-form-wrap label {
            display: block;
            font-size: 0.95rem;
            font-weight: 500;
            margin-bottom: 0.3rem;
            color: #333;
            text-transform: capitalize;
        }
        .contact-form-wrap input,
        .contact-form-wrap textarea {
            width: 100%;
            padding: 1rem;
            margin-bottom: 1.2rem;
            border: 1.5px solid #ddd;
            border-radius: 6px;
            font-size: 1rem;
            font-family: 'Poppins', sans-serif;
            transition: border 0.3s;
            box-sizing: border-box;
            background: #fafaf8;
        }
        .contact-form-wrap input:focus,
        .contact-form-wrap textarea:focus {
            border-color: var(--main-color);
            outline: none;
        }
        .contact-form-wrap textarea {
            height: 140px;
            resize: vertical;
        }
        .contact-form-wrap .submit-btn {
            background: var(--main-color);
            color: #fff;
            padding: 1rem 3rem;
            border: none;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            text-transform: uppercase;
            letter-spacing: 1px;
            transition: background 0.3s;
            border-radius: 4px;
            font-family: 'Poppins', sans-serif;
        }
        .contact-form-wrap .submit-btn:hover {
            background: #1a1a1a;
        }
        .address {
            padding: 4% 6%;
            background: #fafaf8;
        }
        .address .box-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 2rem;
            margin-top: 2rem;
        }
        .address .box-container .box {
            display: flex;
            align-items: center;
            gap: 1.5rem;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            background: #fff;
            border-radius: 10px;
            padding: 1.5rem;
        }
        .address .box-container .box i {
            font-size: 2.5rem;
            color: var(--main-color);
            flex-shrink: 0;
        }
        .address .box-container .box div h4 {
            font-size: 1.1rem;
            text-transform: uppercase;
            color: var(--main-color);
            margin-bottom: 0.3rem;
        }
        .address .box-container .box div p {
            color: #555;
            font-size: 0.95rem;
            line-height: 1.6;
        }
        @media (max-width: 768px) {
            .contact-section {
                grid-template-columns: 1fr;
            }
            .contact-map iframe {
                min-height: 300px;
            }
            .contact-form-wrap {
                padding: 2rem 1.5rem;
            }
        }
    </style>
</head>
<body>

<?php include 'components/user_header.php'; ?>

<!-- Banner -->
<div class="banner">
    <div class="detail">
        <h1>Contact Us</h1>
        <p>We'd love to hear from you. Get in touch with Zari & Co.</p>
        <span>
            <a href="home.php">Home</a>
            <i class="bx bxs-right-arrow-alt"></i>
            Contact
        </span>
    </div>
</div>

<div class="line2"></div>

<!-- Contact Info Cards -->
<div class="address">
    <div class="heading">
        <h1>Get in Touch</h1>
        <span>We're here to help with anything you need</span>
    </div>
    <div class="box-container">
        <div class="box">
            <i class="bx bxs-phone"></i>
            <div>
                <h4>Call Us</h4>
                <p>+91-98200-12345<br>Mon–Sat, 10am–6pm IST</p>
            </div>
        </div>
        <div class="box">
            <i class="bx bxs-envelope"></i>
            <div>
                <h4>Email Us</h4>
                <p>hello@zariandco.in<br>We reply within 24 hours</p>
            </div>
        </div>
        <div class="box">
            <i class="bx bxs-location-plus"></i>
            <div>
                <h4>Visit Us</h4>
                <p>Zari & Co. Studio<br>Bandra West, Mumbai 400050, MH</p>
            </div>
        </div>
        <div class="box">
            <i class="bx bxl-whatsapp"></i>
            <div>
                <h4>WhatsApp</h4>
                <p>+91-98200-12345<br>Chat with our style team</p>
            </div>
        </div>
    </div>
</div>

<!-- Map + Contact Form -->
<div class="contact-section">
    <div class="contact-map">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3771.4988!2d72.8364!3d19.0596!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be7c8c01a8faebb%3A0x3b7eec1eb2dd2a05!2sBandra%20West%2C%20Mumbai!5e0!3m2!1sen!2sin!4v1718000000000"
            width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade" title="Zari & Co. Location Map">
        </iframe>
    </div>

    <div class="contact-form-wrap">
        <h2>Send Us a Message</h2>
        <p class="subtitle">Fill in the form below and our team will get back to you shortly.</p>
        <form action="" method="POST">
            <label for="contact-name">Your Name *</label>
            <input type="text" id="contact-name" name="name" placeholder="e.g. Priya Sharma" required>

            <label for="contact-email">Your Email *</label>
            <input type="email" id="contact-email" name="email" placeholder="hello@example.com" required>

            <label for="contact-subject">Subject *</label>
            <input type="text" id="contact-subject" name="subject" placeholder="e.g. Order Query" required>

            <label for="contact-message">Your Message *</label>
            <textarea id="contact-message" name="message" placeholder="Tell us how we can help you..." required></textarea>

            <button type="submit" name="submit_contact" class="submit-btn">
                <i class="bx bx-send"></i> Send Message
            </button>
        </form>
    </div>
</div>

<?php include 'components/user_footer.php'; ?>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>
<script src="js/user_script.js"></script>
<?php include 'components/alert.php'; ?>

</body>
</html>
