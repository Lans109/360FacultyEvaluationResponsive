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
            <h1>Faculty</h1>
        </div>
        <div class="content">
            <div class="upperContent">
                <div class="addBtn">
                    <button id="openModalBtn-add-course" class="add-btn" onclick="openModal('add-faculty')">Add
                        Faculty</button>
                </div>

                <!-- no function yet add at app.js -->
                <div class="sortDropDown">
                    <label for="sort">Sort by:</label>
                    <select id="sort" onchange="sortFaculty()">
                        <option value="newest">Newest</option>
                        <option value="oldest">Oldest</option>
                    </select>
                </div>
            </div>
            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th>Faculty ID</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Email</th>
                            <th>Contact</th>
                            <th>Profile Picture</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Sample data lang replace with queries
                        $faculties = [
                            ["id" => "001", "fname" => "Prince", "lname" => "Pipen", "email" => "stephen.lacsa@lpunetwork.edu.ph", "contact" => "0995-123-4567", "pfp" => ""],
                            ["id" => "002", "fname" => "Lorenzo", "lname" => "Canales", "email" => "lorenzo.canales@lpunetwork.edu.ph", "contact" => "123-456-7890", "pfp" => ""],
                            ["id" => "003", "fname" => "Romuel", "lname" => "Borja", "email" => "romuel.borja@lpunetwork.edu.ph", "contact" => "098-765-4321", "pfp" => ""],
                        ];
                        foreach ($faculties as $faculty) {
                            echo "<tr>
                                <td>{$faculty['id']}</td>
                                <td>{$faculty['fname']}</td>
                                <td>{$faculty['lname']}</td>
                                <td>{$faculty['email']}</td>
                                <td>{$faculty['contact']}</td>
                                <td>{$faculty['pfp']}</td>
                                <td><button class='edit-btn' id='openModalBtn-edit-faculty' onclick='openModal(\"edit-faculty\")'>Edit</button></td>
                            </tr>";
                        }
                        // Div to display faculty details in card format
                        foreach ($faculties as $faculty) {
                            echo "<div class='table-to-cards hidden'>
                                <div class='ttc-faculty_id'>{$faculty['id']}</div>
                                <div class='ttc-faculty_fname'>{$faculty['fname']}</div>
                                <div class='ttc-faculty_lname'>{$faculty['lname']}</div>
                                <div class='ttc-faculty_email'>{$faculty['email']}</div>
                                <div class='ttc-faculty_contact'>{$faculty['contact']}</div>
                                <div class='ttc-faculty_pfp'>{$faculty['pfp']}</div>
                                <div class='ttc_btn-edit_faculty'>
                                    <button class='edit-btn' id='openModalBtn-edit-faculty' onclick='openModal(\"edit-faculty\")'>Edit</button>
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

    <!-- modal for adding faculty -->
    <!-- Modal Structure -->
    <div id="add-faculty" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Add Faculty </div>
                <span class="close" onclick="closeModal('add-faculty')">&times;</span>
            </div>
            <div class="modal-body">
                <form action="POST">
                    <label for="faculty_id">Faculty ID</label><br>
                    <input type="number" id="faculty_id" name="faculty_id" min="0" required><br>
                    <label for="faculty_fname">First Name</label><br>
                    <input type="text" id="faculty_fname" name="faculty_fname" required><br>
                    <label for="faculty_lname">Last Name</label><br>
                    <input type="text" id="faculty_lname" name="faculty_lname" required><br>
                    <label for="faculty_email">Faculty Email</label><br>
                    <input type="text" id="faculty_email" name="faculty_email" required><br>
                    <label for="faculty_contact">Faculty Contact</label><br>
                    <input type="number" id="faculty_contact" name="faculty_contact" required><br>
            </div>
            <div class="modal-footer">
                <button class="cancel-btn" onclick="closeModal('add-faculty')">Cancel</button>
                <input type="submit" class="save-btn" value="Add">
            </div>
            </form>
        </div>
    </div>

    <!-- database retrieval needed -->
    <!-- modal for editing student -->
    <!-- Modal Structure -->
    <div id="edit-faculty" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Edit Faculty </div>
                <span class="close" onclick="closeModal('edit-faculty')">&times;</span>
            </div>
            <div class="modal-body">
                <form action="POST">
                    <label for="faculty_id">Faculty ID</label><br>
                    <input type="number" id="faculty_id" name="faculty_id" min="0" required><br>
                    <label for="faculty_fname">First Name</label><br>
                    <input type="text" id="faculty_fname" name="faculty_fname" required><br>
                    <label for="faculty_lname">Last Name</label><br>
                    <input type="text" id="faculty_lname" name="faculty_lname" required><br>
                    <label for="faculty_email">Faculty Email</label><br>
                    <input type="text" id="faculty_email" name="faculty_email" required><br>
                    <label for="faculty_contact">Faculty Contact</label><br>
                    <input type="number" id="faculty_contact" name="faculty_contact" required><br>
            </div>
            <div class="modal-footer">
                <button class="cancel-btn" onclick="closeModal('edit-faculty')">Cancel</button>
                <input type="submit" class="save-btn" value="Save Changes">
            </div>
            </form>
        </div>
    </div>
</body>

</html>