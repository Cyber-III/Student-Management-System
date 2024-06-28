<?php
// Start the session
session_start();

// Include the database connection
include_once('../connection.php');

// Loading the HTML template
include_once('../assests/content/static/template.php');



?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Article Set</title>

    <link rel="stylesheet" href="../style-template.css">
    <link rel="stylesheet" href="livesearch.css">
</head>
<body>
    <!-- fetching data from database -->
    
    <div class="container_main">
        <h1>Search Articles</h1>
        <form id="searchForm" method="POST" action="search.php">
            <input type="text" id="searchInput" name="keyword" placeholder="Enter keywords..." required>
            <button type="submit" class = "btn1" >Search</button>
        </form>
        <div id="results">
            <!-- Results will be displayed here -->
        </div>
    </div>
<script>
    document.getElementById('searchForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const keyword = document.getElementById('searchInput').value;

    fetch('search.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: 'keyword=' + encodeURIComponent(keyword)
    })
    .then(response => response.text())
    .then(data => {
        document.getElementById('results').innerHTML = data;
    })
    .catch(error => console.error('Error:', error));
});

</script>
    
</body>