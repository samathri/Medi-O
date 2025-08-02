<?php
// Include necessary files and database connection
include 'db_connection.php';

// Check if the action parameter is set in the URL (when the button is clicked)
if (isset($_GET['action']) && $_GET['action'] === 'readyToPick' && isset($_GET['id'])) {
    $prescription_id = $_GET['id'];

    // Fetch customer details for the given prescription ID
    $query = "SELECT * FROM prescriptions WHERE prescription_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $prescription_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $prescription = $result->fetch_assoc();

    if ($prescription) {
        // Get customer details
        $customer_email = $prescription['customer_email'];
        $customer_name = $prescription['customer_name'];
        
        // Send email
        $subject = "Your Prescription is Ready for Pickup";
        $message = "Dear $customer_name,\n\nYour prescription is now ready for pickup at our pharmacy.\n\nThank you for choosing our service.\n\nBest regards,\nMedi-O Team";
        $headers = "From: no-reply@medi-o.com\r\n";
        
        // Send email
        if (mail($customer_email, $subject, $message, $headers)) {
            echo "<script>alert('An email has been sent to the customer.');</script>";
            // Optionally update the prescription status in the database to "Ready to Pick"
            $update_query = "UPDATE prescriptions SET status = 'Ready to Pick' WHERE prescription_id = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("s", $prescription_id);
            $update_stmt->execute();
        } else {
            echo "<script>alert('Failed to send the email.');</script>";
        }
    }
}
?>
