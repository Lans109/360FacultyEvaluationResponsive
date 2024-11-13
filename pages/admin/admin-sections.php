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
            <h1>Sections</h1>
        </div>
        <div class="content">
            <div class="upperContent">
                <div class="addBtn">
                    <button id="openModalBtn-add-course" class="add-btn" onclick="openModal('add-section')">Add
                        Section</button>
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
                            <th>Section code</th>
                            <th>Course</th>
                            <th>Faculty</th>
                            <th>Program</th>
                            <th>No. of Students</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Sample data lang replace with queries
                        $sections = [
                            ["code" => "CSE 301", "course" => "Operating System", "faculty" => "Charles Kensington", "program" => "BSCE", "students" => "20"],
                            ["code" => "IT 301", "course" => "Web Development", "faculty" => "Eleanor Fitzgerald", "program" => "BSIT", "students" => "20"],
                            ["code" => "CSE301", "course" => "Operating System", "faculty" => "Charles Kensington", "program" => "BSCE", "students" => "20"],
                            ["code" => "CS 301", "course" => "Data Structures and Algorithms", "faculty" => "Harper Blake", "program" => "BSCS", "students" => "20"],
                        ];
                        foreach ($sections as $section) {
                            echo "<tr>
                                <td>{$section['code']}</td>
                                <td>{$section['course']}</td>
                                <td>{$section['faculty']}</td>
                                <td>{$section['program']}</td>
                                <td>{$section['students']}</td>
                                <td><button class='edit-btn' id='openModalBtn-edit-section' onclick='openModal(\"edit-section\")'>Edit</button></td>
                            </tr>";
                        }
                        // Div to display section details in card format
                        foreach ($sections as $section) {
                            echo "<div class='table-to-cards hidden'>
                                <div class='ttc-section_code'>{$section['code']}</div>
                                <div class='ttc-section_course'>{$section['course']}</div>
                                <div class='ttc-section_faculty'>{$section['faculty']}</div>
                                <div class='ttc-section_program'>{$section['program']}</div>
                                <div class='ttc-section_students'>{$section['students']}</div>
                                <div class='ttc_btn-edit_section'>
                                    <button class='edit-btn' id='openModalBtn-edit-section' onclick='openModal(\"edit-section\")'>Edit</button>
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

    <!-- modal for adding section -->
    <!-- Modal Structure -->
    <div id="add-section" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Add Section </div>
                <span class="close" onclick="closeModal('add-section')">&times;</span>
            </div>
            <div class="modal-body">
                <form action="POST">
                    <label for="section_code">Section Code</label><br>
                    <input type="text" id="section_code" name="section_code" required><br>
                    <label for="section_course">Section Course</label><br>
                    <input type="text" id="section_course" name="section_course" required><br>
                    <label for="section_faculty">Section Faculty</label><br>
                    <input type="text" id="section_faculty" name="section_faculty" required><br>

                    <label for="section_student">Number of Students</label><br>
                    <input type="number" id="course_students" name="course_students" min="0" required><br>

            </div>
            <div class="modal-footer">
                <button class="cancel-btn" onclick="closeModal('add-section')">Cancel</button>
                <input type="submit" class="save-btn" value="Add">
            </div>
            </form>
        </div>
    </div>

    <!-- database retrieval needed -->
    <!-- modal for editing section -->
    <!-- Modal Structure -->
    <div id="edit-section" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Edit Section </div>
                <span class="close" onclick="closeModal('edit-section')">&times;</span>
            </div>
            <div class="modal-body">
                <form action="POST">
                    <label for="section_code">Section Code</label><br>
                    <input type="text" id="section_code" name="section_code" required><br>
                    <label for="section_course">Section Course</label><br>
                    <input type="text" id="section_course" name="section_course" required><br>
                    <label for="section_faculty">Section Faculty</label><br>
                    <input type="text" id="section_faculty" name="section_faculty" required><br>

                    <label for="section_student">Number of Students</label><br>
                    <input type="number" id="course_students" name="course_students" min="0" required><br>

            </div>
            <div class="modal-footer">
                <button class="cancel-btn" onclick="closeModal('edit-section')">Cancel</button>
                <input type="submit" class="save-btn" value="Save Changes">
            </div>
            </form>
        </div>
    </div>
</body>

</html>