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
            <h1>Programs</h1>
        </div>
        <div class="content">
            <div class="upperContent">
                <div class="addBtn">
                    <button id="openModalBtn-add-course" class="add-btn" onclick="openModal('add-program')">Add
                        Program</button>
                </div>

                <!-- no function yet add at app.js -->
                <div class="sortDropDown">
                    <label for="sort">Sort by:</label>
                    <select id="sort" onchange="sortPrograms()">
                        <option value="newest">Newest</option>
                        <option value="oldest">Oldest</option>
                    </select>
                </div>
            </div>
            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th>Program code</th>
                            <th>Program name</th>
                            <th>Program description</th>
                            <th>Department</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Sample data lang replace with queries
                        $programs = [
                            ["code" => "BSCE", "name" => "Bachelor of Science in Computer Engineering", "description" => "Design and integration of hardware and software systems", "department" => "COECSA"],
                            ["code" => "BSIT", "name" => "Bachelor of Science in Information Technology", "description" => "Prepares for careers in IT with emphasis on system development and network administration", "department" => "COECSA"],
                            ["code" => "BSCS", "name" => "Bachelor of Science in Computer Science", "description" => "Focus on software development, algorithm design, and data structures", "department" => "COECSA"],
                        ];
                        foreach ($programs as $program) {
                            echo "<tr>
                                <td>{$program['code']}</td>
                                <td>{$program['name']}</td>
                                <td>{$program['description']}</td>
                                <td>{$program['department']}</td>
                                <td><button class='edit-btn' id='openModalBtn-edit-program' onclick='openModal(\"edit-program\")'>Edit</button></td>
                            </tr>";
                        }
                        // Div to display program details in card format
                        foreach ($programs as $program) {
                            echo "<div class='table-to-cards hidden'>
                                <div class='ttc-program_code'>{$program['code']}</div>
                                <div class='ttc-program_name'>{$program['name']}</div>
                                <div class='ttc-program_description'>{$program['description']}</div>
                                <div class='ttc-program_department'>{$program['department']}</div>
                                <div class='ttc_btn-edit_program'>
                                    <button class='edit-btn' id='openModalBtn-edit-program' onclick='openModal(\"edit-program\")'>Edit</button>
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

    <!-- modal for adding programs -->
    <!-- Modal Structure -->
    <div id="add-program" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Add Program </div>
                <span class="close" onclick="closeModal('add-program')">&times;</span>
            </div>
            <div class="modal-body">
                <form action="POST">
                    <label for="program_code">Program Code</label><br>
                    <input type="text" id="program_code" name="program_code" required><br>
                    <label for="program_name">Program Name</label><br>
                    <input type="text" id="program_name" name="program_name" required><br>
                    <label for="program_description">Program Description</label><br>
                    <textarea id="program_description" name="program_description" required>

                    </textarea><br>
                    <label for="program_department">Program Department</label>
                    <!-- database query here (only placeholder)-->
                    <br><select name="program_department" id="program_department">
                        <option value="CAMS">CAMS</option>
                        <option value="CLAE">CLAE</option>
                        <option value="CBA">CBA</option>
                        <option value="COECSA">COECSA</option>
                        <option value="CFAD">CFAD</option>
                        <option value="CITHM">CITHM</option>
                        <option value="CON">CON</option>
                    </select>
                    <br>
            </div>
            <div class="modal-footer">
                <button class="cancel-btn" onclick="closeModal('add-program')">Cancel</button>
                <input type="submit" class="save-btn" value="Add">
            </div>
            </form>
        </div>
    </div>

    <!-- database retrieval needed -->
    <!-- modal for editing programs -->
    <!-- Modal Structure -->
    <div id="edit-program" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Add Program </div>
                <span class="close" onclick="closeModal('edit-program')">&times;</span>
            </div>
            <div class="modal-body">
                <form action="POST">
                    <label for="program_code">Program Code</label><br>
                    <input type="text" id="program_code" name="program_code" required><br>
                    <label for="program_name">Program Name</label><br>
                    <input type="text" id="program_name" name="program_name" required><br>
                    <label for="program_description">Program Description</label><br>
                    <textarea id="program_description" name="program_description" required>

                    </textarea><br>
                    <label for="program_department">Program Department</label>
                    <!-- database query here (only placeholder)-->
                    <br><select name="program_department" id="program_department">
                        <option value="CAMS">CAMS</option>
                        <option value="CLAE">CLAE</option>
                        <option value="CBA">CBA</option>
                        <option value="COECSA">COECSA</option>
                        <option value="CFAD">CFAD</option>
                        <option value="CITHM">CITHM</option>
                        <option value="CON">CON</option>
                    </select>
                    <br>
            </div>
            <div class="modal-footer">
                <button class="cancel-btn" onclick="closeModal('edit-program')">Cancel</button>
                <input type="submit" class="save-btn" value="Save Changes">
            </div>
            </form>
        </div>
    </div>

</body>

</html>