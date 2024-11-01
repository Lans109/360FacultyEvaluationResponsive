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
            <h1>Departments</h1>
        </div>
        <div class="content">
            <div class="upperContent">
                <div class="addBtn">
                    <button id="openModalBtn-add-course" class="add-btn" onclick="openModal('add-department')">Add
                        Student</button>
                </div>

                <!-- no function yet add at app.js -->
                <div class="sortDropDown">
                    <label for="sort">Sort by:</label>
                    <select id="sort" onchange="sortDepartment()">
                        <option value="newest">Newest</option>
                        <option value="oldest">Oldest</option>
                    </select>
                </div>
            </div>
            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th>Department Code</th>
                            <th>Department Name</th>
                            <th>Description</th>
                            <th>Program Chair</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Sample data lang replace with queries
                        $departments = [
                            ["code" => "COECSA", "name" => "College of Engineering and Computer Science and Architecture", "description" => "Focuses on programs in engineering, computer science, and architecture, providing students with a robust technical and practical foundation.", "program_chair" => "Dr. James Tucker"],
                            ["code" => "CITHM", "name" => "College of International Tourism and Hospitality Management", "description" => "Offers programs in tourism and hospitality, emphasizing global standards in service and management.", "program_chair" => "Prof. Sarah Lim"],
                            ["code" => "CAS", "name" => "College of Arts and Sciences", "description" => "Provides a broad range of programs in the arts and sciences, fostering critical thinking and creativity.", "program_chair" => "Dr. Alice Rios"]
                        ];
                        foreach ($departments as $department) {
                            echo "<tr>
                                <td>{$department['code']}</td>
                                <td>{$department['name']}</td>
                                <td>{$department['description']}</td>
                                <td>{$department['program_chair']}</td>
                                <td><button class='edit-btn' id='openModalBtn-edit-department' onclick='openModal(\"edit-department\")'>Edit</button></td>
                            </tr>";
                        }
                        // Div to display departments details in card format
                        foreach ($departments as $department) {
                            echo "<div class='table-to-cards hidden'>
                                <div class='ttc-department_code'>{$department['code']}</div>
                                <div class='ttc-department_name'>{$department['name']}</div>
                                <div class='ttc-department_description'>{$department['description']}</div>
                                <div class='ttc-department_program_chair'>{$department['program_chair']}</div>
                                <div class='ttc_btn-edit_departments'>
                                    <button class='edit-btn' id='openModalBtn-edit-department' onclick='openModal(\"edit-department\")'>Edit</button>
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

    <!-- modal for adding department -->
    <!-- Modal Structure -->
    <div id="add-department" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Add Department </div>
                <span class="close" onclick="closeModal('add-department')">&times;</span>
            </div>
            <div class="modal-body">
            <form action="POST">
                    <label for="department_code">Department Code</label><br>
                    <input type="text" id="department_code" name="department_code" required><br>
                    <label for="department_name">Department Name</label><br>
                    <input type="text" id="course_name" name="course_name" required><br>
                    <label for="department_description">Department Description</label><br>
                    <textarea id="department_description" name="department_description" required>

                    </textarea><br>
                    <label for="department_program_chair">Program Chair</label><br>
                    <input type="text" id="department_program_chair" name="department_program_chair" required><br>

            </div>
            <div class="modal-footer">
                <button class="cancel-btn" onclick="closeModal('add-department')">Cancel</button>
                <input type="submit" class="save-btn" value="Add">
            </div>
            </form>
        </div>
    </div>

    <!-- database retrieval needed -->
    <!-- modal for editing student -->
    <!-- Modal Structure -->
    <div id="edit-department" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Edit Department </div>
                <span class="close" onclick="closeModal('edit-department')">&times;</span>
            </div>
            <div class="modal-body">
            <form action="POST">
                    <label for="department_code">Department Code</label><br>
                    <input type="text" id="department_code" name="department_code" required><br>
                    <label for="department_name">Department Name</label><br>
                    <input type="text" id="course_name" name="course_name" required><br>
                    <label for="department_description">Department Description</label><br>
                    <textarea id="department_description" name="department_description" required>

                    </textarea><br>
                    <label for="department_program_chair">Program Chair</label><br>
                    <input type="text" id="department_program_chair" name="department_program_chair" required><br>

            </div>
            <div class="modal-footer">
                <button class="cancel-btn" onclick="closeModal('edit-department')">Cancel</button>
                <input type="submit" class="save-btn" value="Save Changes">
            </div>
            </form>
        </div>
    </div>
</body>

</html>