<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='admin-style.css'>
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />
    <script type="text/javascript" src="app.js" defer></script>
    <title>360 Faculty Evaluation System</title>

    <nav class="topnav">
        <span class="open-menu" onclick="toggleSidebar()">☰</span>
    </nav>
</head>

<body>
    <?php include 'admin.sidebar.php'; ?>

    <main>
        <div class="upperMain">
            <h1>Students</h1>
        </div>
        <div class="content">
            <div class="upperContent">
                <div class="addBtn">
                    <button id="openModalBtn-add-course" class="add-btn" onclick="openModal('add-student')">Add
                        Student</button>
                </div>

                <!-- no function yet add at app.js -->
                <div class="sortDropDown">
                    <label for="sort">Sort by:</label>
                    <select id="sort" onchange="sortSections()">
                        <option value="newest">Newest</option>
                        <option value="oldest">Oldest</option>
                    </select>
                </div>
            </div>
            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th>Student ID</th>
                            <th>Name</th>
                            <th>Course</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Course Enrolled</th>
                            <th>Enrollment Status</th>
                            <th>Profile Picture</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Sample data lang replace with queries
                        $students = [
                            ["id" => "2022-2-00568", "name" => "Prince Pipen", "course" => "BSCS", "email" => "stephen.lacsa@lpunetwork.edu.ph", "phone" => "0995-123-4567", "course_enrolled" => "CSCN10C", "enrollment_status" => "Enrolled", "pfp" => ""],
                            ["id" => "2022-2-00883", "name" => "Lance Romero", "course" => "BSCS", "email" => "lance.romero@lpunetwork.edu.ph", "phone" => "0949-306-8899", "course_enrolled" => "CSCN10C", "enrollment_status" => "Enrolled", "pfp" => ""],
                            ["id" => "2022-2-01234", "name" => "John Doe", "course" => "BSCS", "email" => "john.doe@lpunetwork.edu.ph", "phone" => "0912-345-6789", "course_enrolled" => "CSCN10C", "enrollment_status" => "Enrolled", "pfp" => ""]
                        ];
                        foreach ($students as $student) {
                            echo "<tr>
                                <td>{$student['id']}</td>
                                <td>{$student['name']}</td>
                                <td>{$student['course']}</td>
                                <td>{$student['email']}</td>
                                <td>{$student['phone']}</td>
                                <td>{$student['course_enrolled']}</td>
                                <td>{$student['enrollment_status']}</td>
                                <td>{$student['pfp']}</td>
                                <td><button class='edit-btn' id='openModalBtn-edit-student' onclick='openModal(\"edit-student\")'>Edit</button></td>
                            </tr>";
                        }
                        // Div to display student details in card format
                        foreach ($students as $student) {
                            echo "<div class='table-to-cards hidden'>
                                <div class='ttc-student_id'>{$student['id']}</div>
                                <div class='ttc-student_name'>{$student['name']}</div>
                                <div class='ttc-student_course'>{$student['course']}</div>
                                <div class='ttc-student_email'>{$student['email']}</div>
                                <div class='ttc-student_phone'>{$student['phone']}</div>
                                <div class='ttc-student_course_enrolled'>{$student['course_enrolled']}</div>
                                <div class='ttc-student_enrollment_status'>{$student['enrollment_status']}</div>
                                <div class='ttc-student_pfp'>{$student['pfp']}</div>
                                <div class='ttc_btn-edit_student'>
                                    <button class='edit-btn' id='openModalBtn-edit-student' onclick='openModal(\"edit-student\")'>Edit</button>
                                </div>
                            </div>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="pagination">
                <button>1</button>
                <button>2</button>
                <button>3</button>
                <!-- Add more pagination as needed -->
            </div>
        </div>
    </main>

    <!-- modal for adding student -->
    <!-- Modal Structure -->
    <div id="add-student" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Add Student </div>
                <span class="close" onclick="closeModal('add-student')">&times;</span>
            </div>
            <div class="modal-body">
                <form action="POST">
                    <label for="student_id">Student ID</label><br>
                    <input type="number" id="student_id" name="student_id" min="0" required><br>
                    <label for="student_name">Student Name</label><br>
                    <input type="text" id="student_name" name="student_name" required><br>
                    <label for="student_course">Student Course</label>
                    <br><select name="student_course" id="student_course">
                        <option value="CAMS">CAMS</option>
                        <option value="CLAE">CLAE</option>
                        <option value="CBA">CBA</option>
                        <option value="COECSA">COECSA</option>
                        <option value="CFAD">CFAD</option>
                        <option value="CITHM">CITHM</option>
                        <option value="CON">CON</option>
                    </select>
                    <br>
                    <label for="student_email">Student Email</label><br>
                    <input type="text" id="student_email" name="student_email" required><br>
                    <label for="student_phone">Student Phone</label><br>
                    <input type="number" id="student_phone" name="student_phone" required><br>
                    <label for="student_course_enrolled">Course Enrolled</label><br>
                    <input type="text" id="student_course_enrolled" name="student_course_enrolled" required><br>
                    <label for="student_enrollment_status">Enrollment Status</label>
                    <br><select name="student_enrollment_status" id="student_enrollment_status">
                        <option value="Enrolled">Enrolled</option>
                        <option value="Not_Enrolled">Not Enrolled</option>
                    </select>
                    <br>
            </div>
            <div class="modal-footer">
                <button class="cancel-btn" onclick="closeModal('add-student')">Cancel</button>
                <input type="submit" class="save-btn" value="Add">
            </div>
            </form>
        </div>
    </div>

    <!-- database retrieval needed -->
    <!-- modal for editing student -->
    <!-- Modal Structure -->
    <div id="edit-student" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Add Student </div>
                <span class="close" onclick="closeModal('edit-student')">&times;</span>
            </div>
            <div class="modal-body">
                <form action="POST">
                    <label for="student_id">Student ID</label><br>
                    <input type="number" id="student_id" name="student_id" min="0" required><br>
                    <label for="student_name">Student Name</label><br>
                    <input type="text" id="student_name" name="student_name" required><br>
                    <label for="student_course">Student Course</label>
                    <br><select name="student_course" id="student_course">
                        <option value="CAMS">CAMS</option>
                        <option value="CLAE">CLAE</option>
                        <option value="CBA">CBA</option>
                        <option value="COECSA">COECSA</option>
                        <option value="CFAD">CFAD</option>
                        <option value="CITHM">CITHM</option>
                        <option value="CON">CON</option>
                    </select>
                    <br>
                    <label for="student_email">Student Email</label><br>
                    <input type="text" id="student_email" name="student_email" required><br>
                    <label for="student_phone">Student Phone</label><br>
                    <input type="number" id="student_phone" name="student_phone" required><br>
                    <label for="student_course_enrolled">Course Enrolled</label><br>
                    <input type="text" id="student_course_enrolled" name="student_course_enrolled" required><br>
                    <label for="student_enrollment_status">Enrollment Status</label>
                    <br><select name="student_enrollment_status" id="student_enrollment_status">
                        <option value="Enrolled">Enrolled</option>
                        <option value="Not_Enrolled">Not Enrolled</option>
                    </select>
                    <br>
            </div>
            <div class="modal-footer">
                <button class="cancel-btn" onclick="closeModal('edit-student')">Cancel</button>
                <input type="submit" class="save-btn" value="Save Changes">
            </div>
            </form>
        </div>
    </div>
</body>

</html>