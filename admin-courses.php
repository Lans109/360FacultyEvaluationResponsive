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
            <h1>Courses</h1>
        </div>
        <div class="content">
            <div class="upperContent">
                <div class="addBtn">
                    <button id="openModalBtn-add-course" class="add-btn" onclick="openModal('add-course')">Add
                        Course</button>
                </div>

                <!-- no function yet add at app.js -->
                <div class="sortDropDown">
                    <label for="sort">Sort by:</label>
                    <select id="sort" onchange="sortCourses()">
                        <option value="newest">Newest</option>
                        <option value="oldest">Oldest</option>
                    </select>
                </div>
            </div>
            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th>Course code</th>
                            <th>Course name</th>
                            <th>Course description</th>
                            <th>Course department</th>
                            <th>No. of students</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Sample data lang replace with queries
                        $courses = [
                            ["code" => "CSCN08C", "name" => "Information Assurance and Security", "description" => "focuses on protecting data confidentiality, integrity, and availability through policies, technologies, and risk management strategies.", "department" => "COECSA", "students" => 25],
                            ["code" => "CSCN07C", "name" => "Architecture and Organization", "description" => "This course focuses on the structure and behavior of the computer system and refers to the logical and abstract aspects of system implementation as seen by the programmer", "department" => "COECSA", "students" => 20]
                        ];
                        foreach ($courses as $course) {
                            echo "<tr>
                                <td>{$course['code']}</td>
                                <td>{$course['name']}</td>
                                <td>{$course['description']}</td>
                                <td>{$course['department']}</td>
                                <td>{$course['students']}</td>
                                <td><button class='edit-btn' id='openModalBtn-edit-course' onclick='openModal(\"edit-course\")'>Edit</button></td>
                            </tr>";
                        }
                        // Div to display course details in card format
                        foreach ($courses as $course) {
                            echo "<div class='table-to-cards hidden'>
                                <div class='ttc-course_code'>{$course['code']}</div>
                                <div class='ttc-course_name'>{$course['name']}</div>
                                <div class='ttc-course_description'>{$course['description']}</div>
                                <div class='ttc-course_department'>{$course['department']}</div>
                                <div class='ttc-number_of_students'>Number of students: {$course['students']}</div>
                                <div class='ttc_btn-edit_course'>
                                    <button class='edit-btn' id='openModalBtn-edit-course' onclick='openModal(\"edit-course\")'>Edit</button>
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

    <!-- modal for adding course -->
    <!-- Modal Structure -->
    <div id="add-course" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Add Course </div>
                <span class="close" onclick="closeModal('add-course')">&times;</span>
            </div>
            <div class="modal-body">
                <form action="POST">
                    <label for="course_code">Course Code</label><br>
                    <input type="text" id="course_code" name="course_code" required><br>
                    <label for="course_name">Course Name</label><br>
                    <input type="text" id="course_name" name="course_name" required><br>
                    <label for="course_description">Course Description</label><br>
                    <textarea id="course_description" name="course_description" required>

                    </textarea><br>
                    <label for="course_department">Course Department</label>
                    <!-- database query here (only placeholder)-->
                    <br><select name="course_department" id="course_department">
                        <option value="CAMS">CAMS</option>
                        <option value="CLAE">CLAE</option>
                        <option value="CBA">CBA</option>
                        <option value="COECSA">COECSA</option>
                        <option value="CFAD">CFAD</option>
                        <option value="CITHM">CITHM</option>
                        <option value="CON">CON</option>
                    </select>
                    <br>
                    <label for="course_students">Number of Students</label><br>
                    <input type="number" id="course_students" name="course_students" min="0" required><br>

            </div>
            <div class="modal-footer">
                <button class="cancel-btn" onclick="closeModal('add-course')">Cancel</button>
                <input type="submit" class="save-btn" value="Add">
            </div>
            </form>
        </div>
    </div>

    <!-- database retrieval needed -->
    <!-- modal for editing course -->
    <!-- Modal Structure -->
    <div id="edit-course" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <!-- can use databse retrieval to add editing () course -->
                <div class="modal-title">Edit Course </div>
                <span class="close" onclick="closeModal('edit-course')">&times;</span>
            </div>
            <div class="modal-body">
                <form action="POST">
                    <label for="course_code">Course Code</label><br>
                    <input type="text" id="course_code" name="course_code" required><br>
                    <label for="course_name">Course Name</label><br>
                    <input type="text" id="course_name" name="course_name" required><br>
                    <label for="course_description">Course Description</label><br>
                    <textarea id="course_description" name="course_description" required>

                    </textarea><br>
                    <label for="course_department">Course Department</label>
                    <!-- database query here (only placeholder)-->
                    <br><select name="course_department" id="course_department">
                        <option value="CAMS">CAMS</option>
                        <option value="CLAE">CLAE</option>
                        <option value="CBA">CBA</option>
                        <option value="COECSA">COECSA</option>
                        <option value="CFAD">CFAD</option>
                        <option value="CITHM">CITHM</option>
                        <option value="CON">CON</option>
                    </select>
                    <br>
                    <label for="course_students">Number of Students</label><br>
                    <input type="number" id="course_students" name="course_students" min="0" required><br>

            </div>
            <div class="modal-footer">
                <button class="cancel-btn" onclick="closeModal('edit-course')">Cancel</button>
                <input type="submit" class="save-btn" value="Save Changes">
            </div>
            </form>
        </div>
    </div>

</body>

</html>