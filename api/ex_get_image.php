<?php
// Check if the image parameter is set
if (isset($_GET['image'])) {
    $imageFilename = $_GET['image'];
    $imagePath = "uploads/" . $imageFilename;

    // Check if the image file exists
    if (file_exists($imagePath)) {
        // Set the appropriate Content-Type header based on the image type
        $imageInfo = getimagesize($imagePath);
        header("Content-Type: " . $imageInfo['mime']);

        // Output the image content
        readfile($imagePath);
    } else {
        // Image file not found
        http_response_code(404);
        echo "Image not found.";
    }
} else {
    // Image parameter is missing
    http_response_code(400);
    echo "Image parameter is missing.";
}
?>
