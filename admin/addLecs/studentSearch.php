<?php
// Start the session
session_start();

// Include the database connection
include_once('../connection.php');

// Loading the HTML template
include_once('../assests/content/static/template.php');

// Initialize sets
$courses = [];
$award_unis = [];

// Fetch courses
$result = mysqli_query($conn, "SELECT DISTINCT course_name FROM course_tbl");
while ($row = mysqli_fetch_assoc($result)) {
    $courses[] = $row['course_name'];
}

// Fetch awarding universities
$result1 = mysqli_query($conn, "SELECT DISTINCT award_uni FROM course_tbl");
while ($row = mysqli_fetch_assoc($result1)) {
    $award_unis[] = $row['award_uni'];
}

// Fetch all data from the course_tbl table
$course_data = [];
$result = mysqli_query($conn, "SELECT * FROM lecturers");
while ($row = mysqli_fetch_assoc($result)) {
    $course_data[] = $row;
}

$message = isset($_GET['message']) ? htmlspecialchars($_GET['message']) : '';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ABC INSTITUTE</title>
    <link rel="stylesheet" href="../style-template.css">
    <link rel="stylesheet" href="style-studentSearch.css">

    <script>
        function validatePassword() {
            const password = document.getElementById('password').value;
            const errorMessage = document.getElementById('password-error');
            const passwordPattern = /^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[\W_]).{8,}$/;

            if (!passwordPattern.test(password)) {
                errorMessage.textContent = "Password must be at least 8 characters long and include an uppercase letter, a lowercase letter, a number, and a special character.";
                return false;
            }

            errorMessage.textContent = "";
            return true;
        }

        function validateForm(event) {
            if (!validatePassword()) {
                event.preventDefault();
            }
        }
    </script>
</head>

<body>

    <?php if ($message == 'insert'): ?>
        <div class="alert alert-success">The Records were created successfully.</div>
    <?php elseif ($message == 'delete'): ?>
        <div class="alert alert-danger">The Records were deleted successfully.</div>
    <?php elseif ($message == 'edit'): ?>
        <div class="alert alert-success">The Records were updated successfully.</div>
    <?php endif; ?>

    <br>

    <form action="batch_create.php" method="POST">
        <div class="form-container">
            <div class="form-row">
                <div class="form-group">
                    <label for="username">Lecturer ID:</label>
                    <input type="text" id="username" name="username" required>
                </div>

                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" id="name" name="name" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required oninput="validatePassword()">
                    <span id="password-error" class="error-message"></span>
                </div>

                <div class="form-group">
                    <label for="department">Department:</label>
                    <input type="text" id="department" name="department" required>
                </div>

            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="dob">Date of Birth:</label>
                    <input type="date" id="dob" name="dob" required>
                </div>

            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="gender">Gender:</label>
                    <select id="gender" name="gender" required>
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="nic">NIC:</label>
                    <input type="text" id="nic" name="nic" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="contact">Contact:</label>
                    <input type="text" id="contact" name="contact" required>
                </div>
            </div>
        </div>
        <br>
        <button type="submit" name="action" value="insert" class="batch view-link">Add Lecturer</button>
    </form>

    <div class="searchbars">
        <!-- Search bar -->
        <div class="search-bar">
            <label for="search">Search by ID:</label>
            <input type="text" id="search" class="search-select" placeholder="Lecturer ID">
            </input>
            <button id="search-icon"><i class="fas fa-search"></i></button>
        </div>

        <br>

    </div>
    

    <!-- Table -->
    <div class="table">
        <table>
            <thead>
                <tr>
                    <th>Lecturer ID</th>
                    <th>Name</th>
                    <th>Department</th>
                    <th>contact</th>
                    <th>Email</th>
                    <th>DOB</th>
                    <th>NIC</th>
                    <th>Gender</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="course-tbody">
                <?php foreach ($course_data as $row): ?>
                    <tr>
                        <td data-lecturer-id="<?= htmlspecialchars($row['username']) ?>"><?= htmlspecialchars($row['username']) ?></td>
                        <td data-name="<?= htmlspecialchars($row['name']) ?>"><?= htmlspecialchars($row['name']) ?></td>
                        <td data-department="<?= htmlspecialchars($row['department']) ?>"><?= htmlspecialchars($row['department']) ?></td>
                        <td data-contact="<?= htmlspecialchars($row['contact']) ?>"><?= htmlspecialchars($row['contact']) ?></td>
                        <td data-email="<?= htmlspecialchars($row['email']) ?>"><?= htmlspecialchars($row['email']) ?></td>
                        <td data-dob="<?= htmlspecialchars($row['dob']) ?>"><?= htmlspecialchars($row['dob']) ?></td>
                        <td data-nic="<?= htmlspecialchars($row['nic']) ?>"><?= htmlspecialchars($row['nic']) ?></td>
                        <td data-gender="<?= htmlspecialchars($row['gender']) ?>"><?= htmlspecialchars($row['gender']) ?></td>

                        <td data-cell="Action">
                            <button class="manage-button view-link" 
                                    data-lecturer-id="<?= htmlspecialchars($row['username']) ?>"
                                    data-name="<?= htmlspecialchars($row['name']) ?>"
                                    data-password="<?= htmlspecialchars($row['password']) ?>"
                                    data-department="<?= htmlspecialchars($row['department']) ?>"
                                    data-email="<?= htmlspecialchars($row['email']) ?>"
                                    data-dob="<?= htmlspecialchars($row['dob']) ?>"
                                    data-gender="<?= htmlspecialchars($row['gender']) ?>"
                                    data-nic="<?= htmlspecialchars($row['nic']) ?>"
                                    data-contact="<?= htmlspecialchars($row['contact']) ?>">
                                Manage
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- manage modal -->
    <div id="manageBatchModal" class="modal">
        <div class="modal-content">
            <span id="closeManageBatchModal" class="close">&times;</span>
            <h2>Manage Lecturer</h2>
            <form id="manageBatchForm" action="batch_manage.php" method="POST">
                <input type="hidden" id="manage_lecturer_id" name="username">
                <div class="form-group">
                    <label for="manage_name">Name:</label>
                    <input type="text" id="manage_name" name="name" required>
                </div>
                <div class="form-group">
                    <label for="manage_password">Password:</label>
                    <input type="password" id="manage_password" name="password" required>
                </div>
                <div class="form-group">
                    <label for="manage_department">Department:</label>
                    <input type="text" id="manage_department" name="department" required>
                </div>
                <div class="form-group">
                    <label for="manage_email">Email:</label>
                    <input type="email" id="manage_email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="manage_dob">Date of Birth:</label>
                    <input type="date" id="manage_dob" name="dob" required>
                </div>
                <div class="form-group">
                    <label for="manage_gender">Gender:</label>
                    <select id="manage_gender" name="gender" required>
                        <option value="">Select Gender</option>
                        <option value="Male">Male</option>
                        <option value="Female">Female</option>
                        <option value="Other">Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="manage_nic">NIC:</label>
                    <input type="text" id="manage_nic" name="nic" required>
                </div>
                <div class="form-group">
                    <label for="manage_contact">Contact:</label>
                    <input type="text" id="manage_contact" name="contact" required>
                </div>
                <br>
                <button type="submit" name="action" value="edit" class="batch view-link">Edit</button>
                <button type="submit" name="action" value="delete" class="batch delete-link">Delete</button>
            </form>
        </div>
    </div>


    <script>
        var manageBatchModal = document.getElementById("manageBatchModal");
        var closeManageBatchModal = document.getElementById("closeManageBatchModal");

        // Close modal when close button is clicked
        closeManageBatchModal.onclick = function() {
            manageBatchModal.style.display = "none";
        }

        // Close modal when user clicks outside of it
        window.onclick = function(event) {
            if (event.target == manageBatchModal) {
                manageBatchModal.style.display = "none";
            }
        }

        // Manage Lecturer functionality
        document.querySelectorAll('.manage-button').forEach(button => {
            button.addEventListener('click', function() {
                var lecturerId = this.dataset.lecturerId;
                var name = this.dataset.name;
                var password = this.dataset.password;
                var department = this.dataset.department;
                var email = this.dataset.email;
                var dob = this.dataset.dob;
                var gender = this.dataset.gender;
                var nic = this.dataset.nic;
                var contact = this.dataset.contact;

                document.getElementById('manage_lecturer_id').value = lecturerId;
                document.getElementById('manage_name').value = name;
                document.getElementById('manage_password').value = password;
                document.getElementById('manage_department').value = department;
                document.getElementById('manage_email').value = email;
                document.getElementById('manage_dob').value = dob;
                document.getElementById('manage_gender').value = gender;
                document.getElementById('manage_nic').value = nic;
                document.getElementById('manage_contact').value = contact;

                manageBatchModal.style.display = "block";
            });
        });

        // Confirm deletion for Lecturer
        document.getElementById("manageBatchForm").addEventListener("submit", function(event) {
            var action = document.activeElement.value;
            if (action === 'delete') {
                var confirmDelete = confirm("Are you sure to delete this lecturer?");
                if (!confirmDelete) {
                    event.preventDefault();
                }
            }
        });


        // Search functionality for Lecturer ID
        document.getElementById("search-icon").addEventListener("click", function() {
            var searchValue = document.getElementById("search").value.toLowerCase();
            var tableRows = document.querySelectorAll("#course-tbody tr");

            tableRows.forEach(function(row) {
                var lecturerIdCell = row.querySelector("[data-lecturer-id]").innerText.toLowerCase();
                if (searchValue === "" || lecturerIdCell.includes(searchValue)) {
                    row.style.display = "";
                } else {
                    row.style.display = "none";
                }
            });
        });


</script>


</body>
</html>
