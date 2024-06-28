<?php

include_once('../connection.php');


                //fetching data from keywords
        if (isset($_POST['keyword'])) {
            $keyword = $_POST['keyword'];
            $sql = "SELECT title, author_id, last_updated, link, category FROM articleSet 
                    WHERE title LIKE ? OR author_id LIKE ? OR category LIKE ?";
            $stmt = $conn->prepare($sql);
            $likeKeyword = "%" . $keyword . "%";
            $stmt->bind_param("sss", $likeKeyword, $likeKeyword, $likeKeyword);
            $stmt->execute();
            $result = $stmt->get_result();
            
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<div>";
                    echo "<h2>" . $row["title"] . "</h2>";
                    echo "<p>Author ID: " . $row["author_id"] . "</p>";
                    echo "<p>Last Updated: " . $row["last_updated"] . "</p>";
                    echo "<p>Category: " . $row["category"] . "</p>";
                    echo "<a href='" . $row["link"] . "'>Read more</a>";
                    echo "</div>";
                }
            } else {
                echo "No results found";
            }
            $stmt->close();
        }
     ?>