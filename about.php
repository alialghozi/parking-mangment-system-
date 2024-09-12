<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us | Autonomous Parking System</title>
    <link rel="stylesheet" href="styles.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
      .hero-section {
    align-items: justififyed;
    height: 100%;
      }
      .container {
    padding: 40px 20px;
    background: #fff;; /* Slightly transparent background */
    border-radius: 12px;
    box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); /* Light shadow for depth */
    max-width: 1200px; /* Limit width for readability */
    margin: 40px auto; /* Center the container with margin */
    margin-top: 100px;
}

.container header h1 {
    font-size: 36px;
    font-weight: bold;
    color: #333;
    text-align: center;
    margin-bottom: 20px;
}

.container p {
    font-size: 18px;
    line-height: 1.6;
    color: #555;
    margin-bottom: 20px;
}

.container h2 {
    font-size: 28px;
    font-weight: bold;
    color: #1e88e5;
    margin-top: 30px;
    margin-bottom: 15px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.container ul {
    list-style-type: disc;
    padding-left: 20px;
    margin-bottom: 20px;
}

.container ul li {
    font-size: 18px;
    line-height: 1.6;
    margin-bottom: 10px;
    color: #555;
}

.container ul li strong {
    color: #1e88e5; /* Highlight strong text in list items */
}

.container p strong {
    color: #1e88e5; /* Highlight strong text in paragraphs */
}

@media (max-width: 768px) {
    .container {
        padding: 20px 15px;
    }

    .container header h1 {
        font-size: 28px;
    }

    .container h2 {
        font-size: 24px;
    }

    .container p, .container ul li {
        font-size: 16px;
    }
}
    </style>
</head>
<body>

    <!-- Navbar -->
    <div class="hero-section">
        <nav>
            <div class="logo">
                <img src="freepik_br_f491d4a1-3c0a-42b7-9055-50e598ef1f93.png" alt="Garage Repair Logo" class="logo-image">
            </div>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="about.php">About</a></li>
                <li><a href="#">Features</a></li>
                <li><a href="#">Implementation</a></li>
                <li><a href="#">Contact</a></li>
            </ul>
            <div class="sign-in"><a href="logout.php">Log out</a></div>
        </nav>
        <!-- About Us Section -->
    <div class="container">
        <header>
            <h1>About Us</h1>
        </header>
        <p>
            Welcome to <strong>[Autonomous Parking System]</strong>, your go-to solution for seamless parking management. Our platform is designed to simplify the parking experience for both individuals and businesses, offering an intuitive and efficient system for booking, managing, and paying for parking spaces.
        </p>

        <h2>Our Mission</h2>
        <p>
            At <strong>[Autonomous Parking System]</strong>, our mission is to create a hassle-free parking experience that saves time and maximizes convenience. Whether you're reserving a spot for a short stay or managing long-term parking for employees, our system ensures that you have access to real-time availability, secure payments, and reliable support.
        </p>

        <h2>What We Offer</h2>
        <ul>
            <li><strong>Real-Time Parking Availability:</strong> Check live updates on parking spot availability and make reservations in advance to secure your space.</li>
            <li><strong>Easy Payment Integration:</strong> Our platform supports a range of payment options, allowing for quick and secure transactions.</li>
            <li><strong>User-Friendly Booking:</strong> Whether you need a spot for a few hours or several days, our booking system is designed to accommodate your schedule.</li>
            <li><strong>Admin Management:</strong> For businesses and institutions, our comprehensive admin panel offers tools to manage multiple parking spots, users, and employee accounts effortlessly.</li>
            <li><strong>Advanced Security Features:</strong> Safety is our priority. We offer features like license plate recognition and security cameras to ensure a secure parking environment.</li>
        </ul>

        <h2>Why Choose Us?</h2>
        <ul>
            <li><strong>Convenience:</strong> Manage all your parking needs from one easy-to-use platform.</li>
            <li><strong>Flexibility:</strong> Our system adapts to individual users, employees, and businesses with customizable options for parking spot management.</li>
            <li><strong>Reliability:</strong> With 24/7 support and real-time updates, you can count on <strong>[Autonomous Parking System]</strong> to provide an efficient parking experience.</li>
            <li><strong>Transparency:</strong> We believe in clear, upfront pricing with no hidden fees. Every booking and transaction is straightforward and secure.</li>
        </ul>

        <h2>Our Vision</h2>
        <p>
            We envision a future where parking is no longer a challenge, but a streamlined service that allows you to focus on what matters most. Whether you’re managing parking for a large corporation or just need a spot for a few hours, <strong>[Autonomous Parking System]</strong> is committed to making parking effortless.
        </p>
    </div>
    </div>

    

    <!-- Footer Section -->
    <div class="footer-section">
        <div class="footer-content">
            <h4>Our Location</h4>
            <p>Jalan Tun Abdul Razak, 05200, Alor Setar, Kedah Darul Aman, Malaysia.</p>
            <p>Phone: +(60)196176860</p>
            <p>Email: <a href="mailto:alialialghuzi@gmail.com">alialialghuzi@gmail.com</a></p>

            <div class="footer-social">
                <a href="#"><i class="fa fa-facebook social-icon"></i></a>
                <a href="#"><i class="fa fa-twitter social-icon"></i></a>
                <a href="#"><i class="fa fa-youtube-play social-icon"></i></a>
                <a href="#"><i class="fa fa-instagram social-icon"></i></a>
            </div>
        </div>

        <div class="footer-bottom">
            <p>© 2024 Parking Management System. All rights reserved.</p>
        </div>
    </div>

</body>
</html>
