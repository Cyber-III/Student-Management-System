<?php
include_once('../connection.php');

// Fetch the latest manual from the database
$query = "SELECT * FROM manuals ORDER BY id DESC LIMIT 1";
$result = mysqli_query($conn, $query);
$manual = mysqli_fetch_assoc($result);

if ($manual) {
    // File path where uploads are stored
    $filePath = '../helpuploads/' . $manual['filename'];
    
    // Set headers for PDF file
    header('Content-Type: application/pdf');
    header('Content-Disposition: attachment; filename="' . $manual['filename'] . '"');
    
    // Output the file data
    readfile($filePath);
} else {
    echo "No manual found.";
}
?>
