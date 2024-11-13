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
            <h1>Accounts</h1>
        </div>
        <div class="content">
            <div class="upperContent">
                <div class="addBtn">
                    <button id="openModalBtn-add-course" class="add-btn" onclick="openModal('add-account')">Add
                        Account</button>
                </div>

                <!-- no function yet add at app.js -->
                <div class="sortDropDown">
                    <label for="sort">Sort by:</label>
                    <select id="sort" onchange="sortAccount()">
                        <option value="newest">Newest</option>
                        <option value="oldest">Oldest</option>
                    </select>
                </div>
            </div>
            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th>User ID</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>User Type</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Sample data lang replace with queries
                        $accounts = [
                            ["id" => "2022-2-00001", "name" => "Liam Thompson", "email" => "liam.t@lpunetwork.edu.ph", "user_type" => "Faculty"],
                            ["id" => "2022-2-00002", "name" => "Ava Martinez", "email" => "ava.m@lpunetwork.edu.ph", "user_type" => "Faculty"],
                            ["id" => "2022-2-00003", "name" => "Noah Johnson", "email" => "noah.j@lpunetwork.edu.ph", "user_type" => "Student"],
                            ["id" => "2022-2-00007", "name" => "Lucas Brown", "email" => "lucas.b@lpunetwork.edu.ph", "user_type" => "Program Chair"]
                        ];
                        foreach ($accounts as $account) {
                            echo "<tr>
                                <td>{$account['id']}</td>
                                <td>{$account['name']}</td>
                                <td>{$account['email']}</td>
                                <td>{$account['user_type']}</td>
                                <td><button class='edit-btn' id='openModalBtn-edit-account' onclick='openModal(\"edit-account\")'>Edit</button></td>
                            </tr>";
                        }
                        // Div to display account details in card format
                        foreach ($accounts as $account) {
                            echo "<div class='table-to-cards hidden'>
                                <div class='ttc-account_id'>{$account['id']}</div>
                                <div class='ttc-account_name'>{$account['name']}</div>
                                <div class='ttc-account_email'>{$account['email']}</div>
                                <div class='ttc-account_user_type'>{$account['user_type']}</div>
                                <div class='ttc_btn-edit_account'>
                                    <button class='edit-btn' id='openModalBtn-edit-account' onclick='openModal(\"edit-account\")'>Edit</button>
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

    <!-- modal for adding account -->
    <!-- Modal Structure -->
    <div id="add-account" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Add Account </div>
                <span class="close" onclick="closeModal('add-account')">&times;</span>
            </div>
            <div class="modal-body">
                <form action="POST">
                    <label for="account_id">User ID</label><br>
                    <input type="text" id="account_id" name="account_id" required><br>
                    <label for="account_name">Full Name</label><br>
                    <input type="text" id="course_name" name="course_name" required><br>
                    <label for="account_email">Email</label><br>
                    <input type="text" id="account_email" name="account_email" required><br>
                    <label for="account_user_type">User Type</label>
                    <!-- database query here (only placeholder)-->
                    <br><select name="account_user_type" id="account_user_type">
                        <option value="student">Student</option>
                        <option value="faculty">Faculty</option>
                        <option value="program_chair">Program Chair</option>
                    </select>
            </div>
        <div class="modal-footer">
            <button class="cancel-btn" onclick="closeModal('add-account')">Cancel</button>
            <input type="submit" class="save-btn" value="Add">
        </div>
        </form>
    </div>
    </div>

    <!-- database retrieval needed -->
    <!-- modal for editing student -->
    <!-- Modal Structure -->
    <div id="edit-account" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <div class="modal-title">Edit Account </div>
                <span class="close" onclick="closeModal('edit-account')">&times;</span>
            </div>
            <div class="modal-body">
                <form action="POST">
                    <label for="account_id">User ID</label><br>
                    <input type="text" id="account_id" name="account_id" required><br>
                    <label for="account_name">Full Name</label><br>
                    <input type="text" id="course_name" name="course_name" required><br>
                    <label for="account_email">Email</label><br>
                    <input type="text" id="account_email" name="account_email" required><br>
                    <label for="account_user_type">User Type</label>
                    <!-- database query here (only placeholder)-->
                    <br><select name="account_user_type" id="account_user_type">
                        <option value="student">Student</option>
                        <option value="faculty">Faculty</option>
                        <option value="program_chair">Program Chair</option>
                    </select>
            </div>
        <div class="modal-footer">
            <button class="cancel-btn" onclick="closeModal('edit-account')">Cancel</button>
            <input type="submit" class="save-btn" value="Save Changes">
        </div>
        </form>
    </div>
    </div>
</body>

</html>