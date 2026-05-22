<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matrimonial Website</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            box-sizing: border-box;
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
        /* Hero Section */
        .hero {
            background: url('images/ring.jpg') no-repeat center center/cover;

            height: 600px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            color: #FFFFFF;
            text-align: center;
        }

        .hero h2 {
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .hero p {
            font-size: 1.2rem;
            margin-bottom: 20px;
        }

        .hero button {
            padding: 10px 20px;
            font-size: 1rem;
            color: #ff5e62;
            background: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        .hero button:hover {
            background:#ff5e62;
            color: white;
        }

        /* Features Section */
        .features {
            padding: 50px 20px;
            text-align: center;
        }

        .features h3 {
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .features .feature-box {
            display: flex;
            gap: 20px;
            justify-content: center;
            margin-top: 30px;
        }

        .features .feature {
            background: #f7f7f7;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 300px;
            transition: transform 0.3s, background-color 0.3s;
        }

        .features .feature:hover {
           transform: scale(1.05);
            background-color: #696969;
            color: white;
        }

        .features .feature h4 {
            font-size: 1.2rem;
            margin-bottom: 10px;
        }

      

        /* Success Stories Section */
        .success-stories {
            padding: 50px 20px;
            background: #f7f7f7;
        }

        .success-stories h3 {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .slider-container {
            position: relative;
            max-width: 1000px;
            margin: auto;
            overflow: hidden;
        }

        .slider {
            display: flex;
            transition: transform 0.5s ease-in-out;
        }

        .story {
            min-width: 300px;
            margin: 0 10px;
            background: #ffe4e1;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .story img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            border-radius: 8px;
        }

        .story h4 {
            font-size: 1.2rem;
            margin-top: 10px;
            color: #ff5e62;
        }

        .story p {
            font-size: 0.9rem;
            color: #555;
            margin-top: 5px;
        }

        .slider-buttons {
            display: flex;
            justify-content: space-between;
            position: absolute;
            top: 50%;
            width: 100%;
            transform: translateY(-50%);
        }

        .slider-buttons button {
            background: #ff5e62;
            color: white;
            border: none;
            padding: 10px;
            cursor: pointer;
            border-radius: 5px;
        }

        .slider-buttons button:hover {
            background: #ff9966;
        }

        /* Paragraph Section */
        .about-section {
            padding: 50px 20px;
            text-align: center;
        }

        .about-section h3 {
            font-size: 2rem;
            margin-bottom: 20px;
        }

        .about-section p {
            font-size: 1rem;
            color: #555;
            line-height: 1.8;
            max-width: 800px;
            margin: 0 auto 30px;
        }

        .about-section a {
            color: #ff5e62;
            text-decoration: none;
            font-weight: 600;
        }

        .about-section a:hover {
            text-decoration: underline;
        }

        /* Footer */
        footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 20px 0;
        }

        footer p {
            margin: 0;
            font-size: 0.9rem;
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

    <div class="hero">
        <h2>Find Your Perfect Match</h2>
        <p>Join thousands of happy couples who found their soulmate with us.</p>
        <button onclick="window.location.href='login.php';">Get Started</button>

    </div>

    <section class="features">
        <h3>Why Choose Us?</h3>
        <div class="feature-box">
            <div class="feature">
                <h4>Verified Profiles</h4>
                <p>All profiles are 100% verified to ensure genuine connections.</p>
            </div>
            <div class="feature">
                <h4>Advanced Matching</h4>
                <p>Our intelligent algorithm connects you with your ideal match.</p>
            </div>
            <div class="feature">
                <h4>Privacy First</h4>
                <p>Your data is always secure with us, ensuring complete privacy.</p>
            </div>
        </div>
    </section>

    <section class="success-stories">
        <h3>Success Stories</h3>
        <div class="slider-container">
            <div class="slider">
                <div class="story">
                    <img src="images/couple1.jpg" alt="Success Story">
                    <h4>John & Mary</h4>
                    <p>"We found love on this platform. It truly changed our lives!"</p>
                </div>
                <div class="story">
                       <img src="images/couple2.jpg" alt="Success Story">
                    <h4>Jane & Michael</h4>
                    <p>"Thank you for helping us find each other. A dream come true!"</p>
                </div>
                <div class="story">
                    <img src="images/couple3.jpg" alt="Success Story">
                    <h4>Sarah & Tom</h4>
                    <p>"Your platform brought us together. Forever grateful!"</p>
                </div>
                <div class="story">
                    <img src="images/couple4.jpg" alt="Success Story">
                    <h4>Lisa & Kevin</h4>
                    <p>"We never thought online matchmaking could be so perfect."</p>
                </div>
                <div class="story">
                    <img src="images/couple5.jpg" alt="Success Story">
                    <h4>Amy & Daniel</h4>
                    <p>"A magical experience that led to a lifetime of happiness!"</p>
                </div>
                <div class="story">
                    <img src="images/couple6.jpg" alt="Success Story">
                    <h4>Emily & Mark</h4>
                    <p>"Finding love here was the best decision we ever made."</p>
                </div>
                <div class="story">
                    <img src="images/couple7.jpg" alt="Success Story">
                    <h4>Anna & Chris</h4>
                    <p>"A heartfelt thank you for uniting us!"</p>
                </div>
                <div class="story">
                    <img src="images/couple8.jpg" alt="Success Story">
                    <h4>Nina & Victor</h4>
                    <p>"Our journey of love began with your amazing website."</p>
                </div>
                <div class="story">
                    <img src="images/couple9.jpg" alt="Success Story">
                    <h4>Olivia & Peter</h4>
                    <p>"The best matchmaking experience ever!"</p>
                </div>
                <div class="story">
                    <img src="images/couple2.jpg" alt="Success Story">
                    <h4>Emma & Ryan</h4>
                    <p>"This platform brought our worlds together. Simply amazing!"</p>
                </div>
            </div>
            <div class="slider-buttons">
                <button onclick="prevSlide()" id="prevBtn" disabled>&#10094;</button>
                <button onclick="nextSlide()" id="nextBtn">&#10095;</button>
            </div>
        </div>
    </section>

    <section class="about-section">
        <h3>About Our Website</h3>
        <p>Our platform is dedicated to connecting individuals with their perfect match through secure and verified profiles. Join our community and be part of countless success stories. Your journey to finding love begins here!</p>
        <a href="contact.php">Contact Us for Help</a>
    </section>

    <footer>
        <p>&copy; 2024 Matrimonial Website. All Rights Reserved.</p>
    </footer>



<script>
  let currentIndex = 0;
let isScrolling = false;

function showSlide(index) {
    const slider = document.querySelector('.slider');
    const totalSlides = document.querySelectorAll('.story').length;

    currentIndex = Math.max(0, Math.min(index, totalSlides - 1));
    const currentTranslate = -currentIndex * 320;
    slider.style.transform = `translateX(${currentTranslate}px)`;

    document.getElementById('nextBtn').disabled = currentIndex >= totalSlides - 1;
    document.getElementById('prevBtn').disabled = currentIndex <= 0;

    setTimeout(() => isScrolling = false, 500); // Add a delay of 500ms between transitions
}

function nextSlide() {
    if (!isScrolling) {
        isScrolling = true;
        showSlide(currentIndex + 1);
    }
}

function prevSlide() {
    if (!isScrolling) {
        isScrolling = true;
        showSlide(currentIndex - 1);
    }
}

function handleWheel(event) {
    if (event.deltaX < 0 && !isScrolling) {
        prevSlide();
    } else if (event.deltaX > 0 && !isScrolling) {
        nextSlide();
    }
}

// Add event listener for touchpad gestures
const slider = document.querySelector('.slider');
slider.addEventListener('wheel', handleWheel);

// Initialize the buttons state
showSlide(currentIndex);


</script>

</body>
</html>

</body>
</html>
