<?php
include_once('../connection.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uploadDir = '../helpuploads/';
    $filename = $_FILES['manualFile']['name'];
    $uploadFile = $uploadDir . basename($filename);
    $tmpFile = $_FILES['manualFile']['tmp_name'];


    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true); 
    }

    
    if (move_uploaded_file($tmpFile, $uploadFile)) {
        
        $handle = fopen($uploadFile, 'rb');
        if ($handle === false) {
            die("Failed to open file for reading.");
        }

        
        $query = "INSERT INTO manuals (manual, filename, filedata) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'sss', $filename, $uploadFile, $filedata);

        // Read and insert file data in chunks
        while (!feof($handle)) {
            $chunk = fread($handle, 8192); 
            mysqli_stmt_send_long_data($stmt, 2, $chunk); 
        }

      
        if (mysqli_stmt_execute($stmt)) {
            echo "File uploaded successfully.";
        } else {
            echo "Error inserting file data: " . mysqli_error($conn);
        }

       
        fclose($handle);
        mysqli_stmt_close($stmt);
    } else {
        echo "Error uploading file.";
    }
}
?>
