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

        .contact-container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 20px;
            text-align: center;
        }

        h1 {
            color: #696969;
        }

        .contact-box {
            background-color: #696969;
            color: white;
            padding: 20px;
            margin: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, background-color 0.3s ease;
        }

        .contact-box:hover {
            transform: scale(1.05);
            background-color: #000000;
        }

        .contact-box h3 {
            font-size: 22px;
            margin-bottom: 10px;
        }

        .contact-box p {
            font-size: 16px;
            line-height: 1.6;
        }

        footer {
            text-align: center;
            padding: 20px;
            background-color: #333;
            color: white;
            margin-top: 50px;
        }

        form {
            max-width: 600px;
            margin: 20px auto;
            text-align: left;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        form label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }

        form input, form textarea, form button {
            width: 100%;
            margin-bottom: 15px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        form button {
            background-color: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        form button:hover {
            background-color: #0056b3;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal-content {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            text-align: center;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            animation: fadeIn 0.5s ease;
        }

        .modal-content h2 {
            color: #007bff;
            margin-bottom: 20px;
        }

        .modal-content p {
            color: #555;
            font-size: 16px;
            margin-bottom: 20px;
        }

        .modal-content button {
            background-color: #007bff;
            color: white;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .modal-content button:hover {
            background-color: #0056b3;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.9);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>
</head>
<body>
    <nav>
        <div class="logo">
           <a href="index.php">
        <img src="images/logo.ico" alt="Website Logo" style="height: 50px; width: auto;"> </a>
        </div>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="about.php">About</a>
            <a href="services.php">Services</a>
            <a href="contact.php">Contact</a>
        </div>
    </nav>

    <div class="contact-container">
        <h1>Contact Us</h1>
        <p>Feel free to reach out to us for any queries or support.</p>

        <div class="contact-box">
            <h3>Helpline Number</h3>
            <p>+91-123-456-7890</p>
        </div>

        <div class="contact-box">
            <h3>Email Support</h3>
            <p>support@matrimonialwebsite.com</p>
        </div>

        <form id="contactForm" action="database/contact_handler.php" method="POST">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>

            <label for="message">Message</label>
            <textarea id="message" name="message" rows="5" required></textarea>

            <button type="submit">Send Message</button>
        </form>
    </div>

    <div class="modal" id="successModal">
        <div class="modal-content">
            <h2>Thank You!</h2>
            <p>Your message has been received. We will get back to you shortly.</p>
            <button onclick="closeModal()">Close</button>
        </div>
    </div>

    <footer>
        <p>&copy; 2024 Matrimonial Website. All Rights Reserved.</p>
    </footer>

  <script>
    const form = document.getElementById('contactForm');
    const modal = document.getElementById('successModal');

   form.addEventListener('submit', function (event) {
    event.preventDefault(); // Prevent default form submission

    // Create FormData object to send the form data via AJAX
    const formData = new FormData(form);

    // Send the data to the server using fetch
    fetch('database/contact_handler.php', {
        method: 'POST',
        body: formData,
    })
        .then(response => response.text())
        .then(data => {
            console.log(data); // Log the response for debugging

            if (data.trim() === 'success') {
                // Show the modal if the submission is successful
                modal.style.display = 'flex';
            } else {
                alert('There was an error submitting the form. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('There was an error. Please try again.');
        });
});


    function closeModal() {
        modal.style.display = 'none';
        form.reset(); // Optional: Reset the form after closing the modal
    }
</script>

</body>
</html>
