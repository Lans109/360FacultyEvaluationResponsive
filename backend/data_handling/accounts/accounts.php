<?php
include_once "../../../config.php";
// Connect to your database
include '../../db/dbconnect.php';

// Fetch program chairs from the database
$query_chairs = "SELECT *, CONCAT(first_name, ' ', last_name) as chair_name, email as chair_email, username as chair_username FROM program_chairs";
$result_chairs = mysqli_query($con, $query_chairs);

// Fetch students from the database
$query_students = "SELECT *, CONCAT(first_name, ' ', last_name) as student_name, email as student_email, username as student_username FROM students";
$result_students = mysqli_query($con, $query_students);

// Fetch faculty from the database
$query_faculty = "SELECT *, CONCAT(first_name, ' ', last_name) as faculty_name, email as faculty_email, username as faculty_username FROM faculty";
$result_faculty = mysqli_query($con, $query_faculty);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Accounts</title>
    <link rel='stylesheet' href='../../../frontend/templates/admin-style.css'>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <?php include '../../../frontend/layout/navbar.php'; ?>
</head>

<body>
    <?php include '../../../frontend/layout/sidebar.php'; ?>

    <main>
        <div class="upperMain">
            <h1>Edit Accounts</h1>
        </div>
        <div class="content">
            <div class="upperContent">

                <!-- no function yet add at app.js
                    <div class="sortDropDown">
                        <label for="sort">Sort by:</label>
                        <select id="sort" onchange="sortCourses()">
                            <option value="newest">Newest</option>
                            <option value="oldest">Oldest</option>
                        </select>
                    </div> -->
            </div>

            <!-- Program Chairs Table -->
            <h4>Program Chairs</h4>
            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th width="370px">Name</th>
                            <th>Username</th> <!-- New Username Column -->
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($chair = mysqli_fetch_assoc($result_chairs)): ?>
                            <tr>
                                <td><?php echo $chair['chair_id']; ?></td>
                                <td><?php echo $chair['chair_name']; ?></td>
                                <td><?php echo $chair['chair_username']; ?></td> <!-- Display Username -->
                                <td><?php echo $chair['chair_email']; ?></td>
                                <td>
                                    <button class="edit-btn" data-toggle="modal"
                                        data-target="#editChairModal<?php echo $chair['chair_id']; ?>"><i
                                            class="fa fa-edit"></i></button>
                                </td>
                            </tr>

                            <!-- Edit Program Chair Modal -->
                            <div class="modal" id="editChairModal<?php echo $chair['chair_id']; ?>" tabindex="-1"
                                role="dialog" aria-labelledby="editChairModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editChairModalLabel">Edit Program Chair</h5>
                                            <span class="close" class="close" data-dismiss="modal"
                                                aria-label="Close">&times;</span>
                                        </div>
                                        <form method="POST" action="update_account.php">
                                            <div class="modal-body">
                                                <input type="hidden" name="chair_id"
                                                    value="<?php echo $chair['chair_id']; ?>">
                                                <div class="form-group">
                                                    <label for="chair_username">Username</label>
                                                    <input type="text" name="chair_username" class="form-control"
                                                        value="<?php echo $chair['chair_username']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="chair_email">Email</label>
                                                    <input type="email" name="chair_email" class="form-control"
                                                        value="<?php echo $chair['chair_email']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="chair_password">Password</label>
                                                    <input type="password" name="chair_password" class="form-control"
                                                        placeholder="Enter new password (leave blank to keep current)">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="cancel-btn" data-dismiss="modal">Close</button>
                                                <button type="submit" class="save-btn">Save changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- Students Table -->
            <h4>Students</h4>
            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Username</th> <!-- New Username Column -->
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($student = mysqli_fetch_assoc($result_students)): ?>
                            <tr>
                                <td><?php echo $student['student_id']; ?></td>
                                <td><?php echo $student['student_name']; ?></td>
                                <td><?php echo $student['student_username']; ?></td> <!-- Display Username -->
                                <td><?php echo $student['student_email']; ?></td>
                                <td>
                                    <button class="edit-btn" data-toggle="modal"
                                        data-target="#editStudentModal<?php echo $student['student_id']; ?>"><i
                                            class="fa fa-edit"></i></button>
                                </td>
                            </tr>

                            <!-- Edit Student Modal -->
                            <div class="modal fade" id="editStudentModal<?php echo $student['student_id']; ?>" tabindex="-1"
                                role="dialog" aria-labelledby="editStudentModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editStudentModalLabel">Edit Student</h5>
                                            <span class="close" class="close" data-dismiss="modal"
                                                aria-label="Close">&times;</span>
                                        </div>
                                        <form method="POST" action="update_account.php">
                                            <div class="modal-body">
                                                <input type="hidden" name="student_id"
                                                    value="<?php echo $student['student_id']; ?>">
                                                <div class="form-group">
                                                    <label for="student_username">Username</label>
                                                    <input type="text" name="student_username" class="form-control"
                                                        value="<?php echo $student['student_username']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="student_email">Email</label>
                                                    <input type="email" name="student_email" class="form-control"
                                                        value="<?php echo $student['student_email']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="student_password">Password</label>
                                                    <input type="password" name="student_password" class="form-control"
                                                        placeholder="Enter new password (leave blank to keep current)">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="cancel-btn" data-dismiss="modal">Close</button>
                                                <button type="submit" class="save-btn">Save changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <!-- Faculty Table -->
            <h4>Faculty</h4>
            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Username</th> <!-- New Username Column -->
                            <th>Email</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($faculty = mysqli_fetch_assoc($result_faculty)): ?>
                            <tr>
                                <td><?php echo $faculty['faculty_id']; ?></td>
                                <td><?php echo $faculty['faculty_name']; ?></td>
                                <td><?php echo $faculty['faculty_username']; ?></td> <!-- Display Username -->
                                <td><?php echo $faculty['faculty_email']; ?></td>
                                <td>
                                    <button class="edit-btn" data-toggle="modal"
                                        data-target="#editFacultyModal<?php echo $faculty['faculty_id']; ?>"><i
                                            class="fa fa-edit"></i></button>
                                </td>
                            </tr>

                            <!-- Edit Faculty Modal -->
                            <div class="modal" id="editFacultyModal<?php echo $faculty['faculty_id']; ?>" tabindex="-1"
                                role="dialog" aria-labelledby="editFacultyModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="editFacultyModalLabel">Edit Faculty</h5>
                                            <span class="close" class="close" data-dismiss="modal"
                                                aria-label="Close">&times;</span>
                                        </div>
                                        <form method="POST" action="update_account.php">
                                            <div class="modal-body">
                                                <input type="hidden" name="faculty_id"
                                                    value="<?php echo $faculty['faculty_id']; ?>">
                                                <div class="form-group">
                                                    <label for="faculty_username">Username</label>
                                                    <input type="text" name="faculty_username" class="form-control"
                                                        value="<?php echo $faculty['faculty_username']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="faculty_email">Email</label>
                                                    <input type="email" name="faculty_email" class="form-control"
                                                        value="<?php echo $faculty['faculty_email']; ?>" required>
                                                </div>
                                                <div class="form-group">
                                                    <label for="faculty_password">Password</label>
                                                    <input type="password" name="faculty_password" class="form-control"
                                                        placeholder="Enter new password (leave blank to keep current)">
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="cancel-btn" data-dismiss="modal">Close</button>
                                                <button type="submit" class="save-btn">Save changes</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

    </main>

    <script type="text/javascript" src="../../../frontend/layout/app.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.0.7/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>