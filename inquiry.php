<?php
// Include your database connection file
include 'includes/db.php';

// Handle Form Submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture form data
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];

    // Sanitize input data to prevent SQL Injection and XSS
    $name = htmlspecialchars($name);
    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars($message);

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
        exit;
    }

    // Prepare SQL query to insert into contact_messages table
    $sql = "INSERT INTO contact_messages (name, email, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    // Bind parameters to prevent SQL injection
    $stmt->bind_param("sss", $name, $email, $message);

    // Execute the query and check if insertion was successful
    if ($stmt->execute()) {
        // Success: Show success message and redirect after closing the modal
        
        echo "<script>
                

                            setTimeout(function() {
                window.location.href = 'contact.php';
            }, 2000); // 2000ms = 2 seconds

              </script>";
        
        echo "
        <div class='modal-overlay'>
            <div class='modal'>
                <h4 class='modal-title'>Success</h4>
                <p class='modal-message'>Thank you for your inquiry. We will get back to you shortly!</p>
                <button class='close-btn' onclick='window.location.href = \"contact.php\";'>Close</button>
            </div>
        </div>
        <style>
            .modal-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background-color: rgba(0, 0, 0, 0.5);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 1000;
            }

            .modal {
                background-color: #4CAF50;
                color: white;
                border-radius: 8px;
                padding: 30px;
                text-align: center;
                box-shadow: 0px 0px 20px rgba(0, 0, 0, 0.1);
                max-width: 400px;
                width: 100%;
            }

            .modal-title {
                font-size: 24px;
                font-weight: bold;
                margin-bottom: 15px;
            }

            .modal-message {
                font-size: 18px;
                margin-bottom: 20px;
            }

            .close-btn {
                background-color: #fff;
                color: #4CAF50;
                padding: 10px 20px;
                border: none;
                border-radius: 5px;
                cursor: pointer;
                font-size: 16px;
                transition: background-color 0.3s ease;
            }

            .close-btn:hover {
                background-color: #f1f1f1;
            }
        </style>
        ";
    } else {
        echo "There was an error submitting your inquiry. Please try again.";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>
