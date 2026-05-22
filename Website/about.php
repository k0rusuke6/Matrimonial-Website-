<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f9;
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

        .about-container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            background-color: #696969;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: white;
        }

        p {
            font-size: 18px;
            line-height: 1.6;
            color: white;
        }

        .highlight {
            color: #ffd700;
            font-weight: bold;
        }

        .animated-section {
            display: flex;
            justify-content: space-around;
            margin-top: 30px;
        }

        .animated-section div {
            background-color: white;
            color: black; /* Updated text color for visibility */
            padding: 20px;
            border-radius: 10px;
            width: 30%;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: background-color 0.5s, color 0.5s;
        }

        .animated-section div:hover {
            background-color: black;
            color: white;
        }

        .animated-section div p {
            transition: color 0.5s;
            color: black; /* Default color */
        }

        .animated-section div:hover p {
            color: white; /* Change to white on hover */
        }

        .team-section {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            text-align: center;
        }

        .team-section h2 {
            color: #333;
        }

        .team-photos {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            margin-top: 20px;
        }

        .team-photos div {
            text-align: center;
            margin: 20px;
        }

        .team-photos img {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .team-photos img:hover {
            transform: scale(1.1);
        }

        .team-photos h3 {
            font-size: 20px;
            margin: 10px 0 5px;
            color: #007bff;
        }

        .team-photos p {
            font-size: 16px;
            color: #555;
            margin: 0;
            transition: color 0.3s ease;
        }

        .team-photos div:hover p {
            color: #007bff;
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #333;
            color: white;
            margin-top: 50px;
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

    <div class="about-container">
        <h1>About Us</h1>
        <p>
            Welcome to our matrimonial website. We are dedicated to helping individuals find their perfect match
            through a secure and reliable platform. Our mission is to connect hearts and create lifelong relationships.
        </p>

        <div class="animated-section">
            <div>
                <h3>Our Mission</h3>
                <p>Connecting individuals and building strong relationships.</p>
            </div>
            <div>
                <h3>Our Vision</h3>
                <p>To be the most trusted matrimonial platform worldwide.</p>
            </div>
            <div>
                <h3>Our Values</h3>
                <p>Integrity, trust, and dedication to our users.</p>
            </div>
        </div>
    </div>

    <div class="team-section">
        <h2>Meet Our Team</h2>
        <div class="team-photos">
            <div>
                <img src="images/team11.png" alt="Team Member 1">
                <h3>John Doe</h3>
                <p>Founder & CEO</p>
            </div>
            <div>
                <img src="images/team22.png" alt="Team Member 2">
                <h3>Jane Smith</h3>
                <p>Chief Technology Officer</p>
            </div>
            <div>
                <img src="images/team33.png" alt="Team Member 3">
                <h3>Emily Johnson</h3>
                <p>Marketing Head</p>
            </div>
            <div>
                <img src="images/team3.jpg" alt="Team Member 4">
                <h3>Michael Brown</h3>
                <p>Lead Developer</p>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Matrimonial Website. All Rights Reserved.</p>
    </footer>
</body>
</html>
