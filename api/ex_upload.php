<?php
// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    // Check if a file was selected
    if (isset($_FILES["image"])) {
        $file = $_FILES["image"];

        // Check for errors during upload
        if ($file["error"] === 0) {
            $uploadDir = "uploads/"; // Specify the upload directory
            $uploadPath = $uploadDir . basename($file["name"]);

            // Move the uploaded file to the specified directory
            if (move_uploaded_file($file["tmp_name"], $uploadPath)) {
                echo "Image uploaded successfully.";
                // You can save the $uploadPath to a database or perform other operations
            } else {
                echo "Error uploading the image.";
            }
        } else {
            echo "Error: " . $file["error"];
        }
    } else {
        echo "No file selected.";
    }
}
?>
