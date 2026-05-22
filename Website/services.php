<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Our Services</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            scroll-behavior: smooth;
        }

        nav {
            background-color: #333;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 20px 40px;
            font-size: 18px;
        }

        nav a {
            color: white;
            text-decoration: none;
            margin: 0 20px;
            font-size: 18px;
        }

        nav a:hover {
            text-decoration: underline;
	color:#FF474C;
        }

        .logo {
            font-size: 24px;
            font-weight: bold;
        }

        .logo a {
            color: white;
            text-decoration: none;
        }

        .logo a:hover {
            text-decoration: none;
        }

        .services-container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            text-align: center;
        }

        h1 {
            color: #696969;
        }

        .service-box {
            background-color: white;
            color: black;
            padding: 20px;
            margin: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, background-color 0.3s ease;
        }

        .service-box:hover {
            transform: scale(1.05);
            background-color: #696969;
            color: white;
        }

        .service-box h3 {
            font-size: 22px;
            margin-bottom: 10px;
        }

        .service-box p {
            font-size: 16px;
            line-height: 1.6;
        }

        .services-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .map-container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            text-align: center;
        }

        .map-container h2 {
            color: #696969;
            margin-bottom: 20px;
        }

        .map {
            width: 100%;
            height: 400px;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #333;
            color: white;
            margin-top: 50px;
        }
 .support-section {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .support-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .support-header h1 {
            color: #696969;
            margin: 0;
        }

        .support-header p {
            color: #555;
            margin: 5px 0 20px;
        }

        .support-grid {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 20px;
        }

        .support-card {
            flex: 1 1 30%;
            text-align: center;
            padding: 20px;
            background-color: #f1f1f1;
            border-radius: 8px;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .support-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .support-card h3 {
            margin: 10px 0;
            color: #333;
        }

        .support-card p {
            color: #777;
            margin: 5px 0;
        }

        .support-card a {
            color: #007bff;
            text-decoration: none;
            font-weight: bold;
        }

        .support-card a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <nav>
        <div class="logo">
             <a href="index.php">
        <img src="images/logo.ico" alt="Website Logo" style="height: 50px; width: auto;">
    </a>
        </div>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="about.php">About</a>
            <a href="services.php">Services</a>
            <a href="contact.php">Contact</a>
        </div>
    </nav>

    <div class="services-container">
        <h1>Our Services</h1>
        <p>Explore the wide range of services we offer to help you find your perfect match.</p>

        <div class="services-grid">
            <div class="service-box">
                <h3>Personalized Matchmaking</h3>
                <p>We offer curated matches based on your preferences and values.</p>
            </div>
            <div class="service-box">
                <h3>Profile Verification</h3>
                <p>Ensuring all profiles are genuine and verified for your safety.</p>
            </div>
            <div class="service-box">
                <h3>Advanced Search Filters</h3>
                <p>Find your ideal match with detailed search filters tailored to you.</p>
            </div>
            <div class="service-box">
                <h3>Success Stories</h3>
                <p>Read inspiring stories of couples who found love through our platform.</p>
            </div>
            <div class="service-box">
                <h3>24/7 Customer Support</h3>
                <p>Our team is always available to assist you with any queries.</p>
            </div>
            <div class="service-box">
                <h3>Privacy & Security</h3>
                <p>Your data is safe with us, ensuring a secure experience.</p>
            </div>
        </div>
    </div>
 <div class="support-section">
        <div class="support-header">
            <h1>Contact Customer Support</h1>
            <p>We’re here to assist you 24/7. Reach out to us anytime.</p>
        </div>
        
        <div class="support-grid">
            <div class="support-card">
                <h3>Call Us</h3>
                <p>Phone: +91-9876543210</p>
                <p>Landline: +91-22-12345678</p>
            </div>
            
            <div class="support-card">
                <h3>Email Us</h3>
                <p>Email: <a href="mailto:support@matrimonial.com">support@matrimonial.com</a></p>
                <p>For urgent queries: <a href="mailto:help@matrimonial.com">help@matrimonial.com</a></p>
            </div>
            
            <div class="support-card">
                <h3>Visit Us</h3>
                <p>Address: Matrimonial Office,<br>123 Main Street, Ghatkopar, Mumbai, India</p>
            </div>
        </div>
    <div class="map-container">
        <h2>Our Location</h2>
   <iframe 
    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3770.5827073451736!2d72.90861697592967!3d19.08665092715247!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3be7c6295d93998d%3A0x7f8b1b6f3c6e839a!2sGhatkopar%2C%20Mumbai%2C%20Maharashtra!5e0!3m2!1sen!2sin!4v1700099999999!5m2!1sen!2sin" 
    width="100%" 
    height="450" 
    style="border:0;" 
    allowfullscreen="" 
    loading="lazy" 
    referrerpolicy="no-referrer-when-downgrade">
    </iframe>

    </div>

    <footer>
        <p>&copy; 2024 Matrimonial Website. All Rights Reserved.</p>
    </footer>
</body>
</html>
