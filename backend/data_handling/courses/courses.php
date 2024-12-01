<?php
// Include configuration and database connection
include_once "../../../config.php";
include ROOT_PATH . '/backend/db/dbconnect.php';

// Authentication check
include '../authentication.php';

### Generate a CSRF token if one doesn't exist ###
if (empty($_SESSION['csrf_token'])) {
	$_SESSION['csrf_token'] = bin2hex(random_bytes(32)); // Generate a random token
}

### Display Status Messages ###
if (isset($_SESSION['status']) && isset($_SESSION['message'])) {
	$status = $_SESSION['status'];
	$message = $_SESSION['message'];

	// Include status handling layout for displaying the message
	include '../../../frontend/layout/status_handling.php';

	// Clear session variables after displaying the message
	unset($_SESSION['status']);
	unset($_SESSION['message']);
}

### Handle Search and Filter Inputs ###
if (isset($_GET['search'])) {
	$_SESSION['search'] = mysqli_real_escape_string($con, $_GET['search']);
}
if (isset($_GET['department_filter'])) {
	$_SESSION['department_filter'] = $_GET['department_filter'];
}

// Use session values if set, otherwise default to empty
$search = $_SESSION['search'] ?? '';
$department_filter = $_SESSION['department_filter'] ?? '';

### Build Query to Fetch Courses ###
$courses_query = "
    SELECT 
        c.course_id, 
        c.course_name, 
        c.course_code, 
        c.course_description, 
        d.department_code, 
        d.department_id
    FROM courses c
    LEFT JOIN departments d ON c.department_id = d.department_id
";

// Add conditions for search and department filter
if ($search) {
	$courses_query .= " WHERE (c.course_name LIKE '%$search%' OR c.course_code LIKE '%$search%')";
}
if ($department_filter) {
	$courses_query .= $search
		? " AND c.department_id = '$department_filter'"
		: " WHERE c.department_id = '$department_filter'";
}

// Execute the query
$courses_result = mysqli_query($con, $courses_query);
$num_rows = mysqli_num_rows($courses_result);

### Reset Filters ###
if (isset($_GET['reset_filters'])) {
	unset($_SESSION['search']);
	unset($_SESSION['department_filter']);
	header("Location: courses.php");
	exit;
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Course Management</title>
	<link rel='stylesheet' href='../../../frontend/templates/admin-style.css'>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<?php include '../../../frontend/layout/confirmation_modal.php'; ?>
	<?php include '../../../frontend/layout/navbar.php'; ?>
</head>

<body>
	<div id="loader" class="loader"></div>
	<?php include '../../../frontend/layout/sidebar.php'; ?>

	<main>
		<!-- Main Header Section -->
		<div class="upperMain">
			<div>
				<h1>Courses Management</h1>
			</div>
		</div>

		<div class="content">
			<!-- Upper Content Section with Search and Filter -->
			<div class="upperContent">
				<div>
					<p>Showing <?= $num_rows ?> <?= $num_rows == 1 ? 'Course' : 'Courses' ?></p>
				</div>
				<!-- Search and Filter Form -->
				<div class="search-filter">
					<form method="GET" action="">
						<div class="form-group">
							<!-- Search Input -->
							<div class="search-container">
								<input type="text" placeholder="Search..." id="search" name="search"
									class="search-input">
								<button type="submit" class="search-button">
									<i class="fa fa-search"></i> <!-- Magnifying Glass Icon -->
								</button>
							</div>

							<!-- Department Filter Dropdown -->
							<div class="select-container">
								<div class="select-wrapper">
									<select id="department_filter" name="department_filter" class="custom-select">
										<option value="" selected>All Departments</option>
										<?php
										// Fetch all departments to populate the filter dropdown
										$departments_query = "SELECT department_id, department_code FROM departments";
										$departments_result = mysqli_query($con, $departments_query);

										// Fetch and display department options
										while ($department = mysqli_fetch_assoc($departments_result)) {
											$selected = (isset($_POST['department_filter']) && $_POST['department_filter'] == $department['department_id']) ? 'selected' : '';
											echo "<option value='" . $department['department_id'] . "' $selected>" . $department['department_code'] . "</option>";
										}
										?>
									</select>
									<i class="fa fa-chevron-down select-icon"></i> <!-- Icon for dropdown -->
								</div>
							</div>

							<!-- Filter and Clear Buttons -->
							<button type="submit" class="fitler-btn">
								<i class="fa fa-filter" aria-hidden="true"></i> Filter
							</button>
							<a href="courses.php?reset_filters=1" class="fitler-btn">
								<i class="fa fa-eraser"></i> Clear
							</a>
						</div>
					</form>
				</div>

				<!-- Add New Course Button -->
				<div>
					<button id="openModalBtn-add-course" class="add-btn" data-toggle="modal" data-target="#addModal">
						<img src="../../../frontend/assets/icons/add.svg">&nbsp;Course&nbsp;
					</button>
				</div>
			</div>

			<!-- Courses Table -->
			<div class="table">
				<table>
					<thead>
						<tr>
							<th width="140px">Course Code</th>
							<th width="300px">Course Name</th>
							<th>Description</th>
							<th width="150px">Department</th>
							<th width="100px">Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php if (mysqli_num_rows($courses_result) > 0): ?>
							<?php while ($course = mysqli_fetch_assoc($courses_result)): ?>
								<tr>
									<td><?php echo $course['course_code']; ?></td>
									<td><?php echo $course['course_name']; ?></td>
									<td><?php echo $course['course_description']; ?></td>
									<td><?php echo $course['department_code'] ?: 'Not Assigned'; ?></td>
									<td>
										<!-- Action Buttons for Edit and Delete -->
										<div class="action-btns">
											<!-- Edit Button -->
											<button class="edit-btn" data-toggle="modal"
												data-target="#editModal<?php echo $course['course_id']; ?>"
												data-id="<?php echo $course['course_id']; ?>"
												data-name="<?php echo $course['course_name']; ?>"
												data-code="<?php echo $course['course_code']; ?>"
												data-description="<?php echo $course['course_description']; ?>"
												data-department-id="<?php echo $course['department_id']; ?>">
												<img src="../../../frontend/assets/icons/edit.svg">
											</button>

											<!-- Delete Button -->
											<form name="deleteForm" action="delete_course.php" method="POST">
												<!-- Hidden input to pass the course_id -->
												<input type="hidden" name="course_id"
													value="<?php echo $course['course_id']; ?>">
												<input type="hidden" name="csrf_token"
													value="<?php echo $_SESSION['csrf_token']; ?>">

												<!-- Submit button for deleting the course -->
												<button type="submit" class="delete-btn">
													<img src="../../../frontend/assets/icons/delete.svg" alt="Delete Icon">
												</button>
											</form>
										</div>
									</td>
								</tr>

								<!-- Edit Course Modal -->
								<div class="modal" id="editModal<?php echo $course['course_id']; ?>" tabindex="-1" role="dialog"
									aria-labelledby="editModalLabel" aria-hidden="true">
									<div class="modal-dialog" role="document">
										<div class="modal-content">
											<div class="modal-header">
												<h5 class="modal-title" id="editModalLabel">Edit Course</h5>
												<span class="close" data-dismiss="modal" aria-label="Close">
													<img src="../../../frontend/assets/icons/close2.svg" alt="Close">
												</span>
											</div>
											<!-- Edit Course Form -->
											<form name="editForm" id="editForm<?php echo $course['course_id']; ?>" method="POST"
												action="update_course.php">
												<input type="hidden" name="csrf_token"
													value="<?php echo $_SESSION['csrf_token']; ?>">
												<div class="modal-body">
													<input type="hidden" name="course_id"
														value="<?php echo $course['course_id']; ?>">
													<div class="form-group">
														<label for="edit_course_name">Course Name</label>
														<input type="text" name="course_name" class="form-control"
															value="<?php echo $course['course_name']; ?>" required>
													</div>
													<div class="form-group">
														<label for="edit_course_code">Course Code</label>
														<input type="text" name="course_code" class="form-control"
															value="<?php echo $course['course_code']; ?>" required>
													</div>
													<div class="form-group">
														<label for="edit_course_description">Description</label>
														<textarea name="course_description" class="form-control"
															required><?php echo $course['course_description']; ?></textarea>
													</div>
													<div class="form-group">
														<label for="edit_department_id">Department</label>
														<select name="department_id" class="form-control"
															id="edit_department_id">
															<option value="">Select Department</option>
															<?php
															// Fetch all departments to populate the dropdown
															$departments_query = "SELECT department_id, department_code FROM departments";
															$departments_result = mysqli_query($con, $departments_query);

															// Display each department in the dropdown
															while ($department = mysqli_fetch_assoc($departments_result)) {
																$selected = ($department['department_id'] == $course['department_id']) ? 'selected' : '';
																echo "<option value='" . $department['department_id'] . "' $selected>" . $department['department_code'] . "</option>";
															}
															?>
														</select>
													</div>
												</div>
												<div class="modal-footer">
													<button type="button" class="cancel-btn" data-dismiss="modal">Close</button>
													<button type="submit" class="save-btn" id="openConfirmationModalBtn">Save
														changes</button>
												</div>
											</form>
										</div>
									</div>
								</div>
							<?php endwhile; ?>
						<?php else: ?>
							<tr>
								<td colspan="4">No Courses found.</td>
							</tr>
						<?php endif; ?>
					</tbody>
				</table>
			</div>
		</div>
	</main>

	<!-- Add Course Modal -->
	<div class="modal" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addCourseModalLabel"
		aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="addCourseModalLabel">Add New Course</h5>
					<span class="close" data-dismiss="modal" aria-label="Close">
						<img src="../../../frontend/assets/icons/close2.svg" alt="Close">
					</span>
				</div>
				<!-- Add Course Form -->
				<form action="add_course.php" method="POST">
					<input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
					<div class="modal-body">
						<div class="form-group">
							<label for="course_name">Course Name</label>
							<input type="text" name="course_name" class="form-control" required>
						</div>
						<div class="form-group">
							<label for="course_code">Course Code</label>
							<input type="text" name="course_code" class="form-control" required>
						</div>
						<div class="form-group">
							<label for="course_description">Course Description</label>
							<textarea name="course_description" class="form-control" required></textarea>
						</div>
						<div class="form-group">
							<label for="department_id">Department</label>
							<select name="department_id" class="form-control">
								<option value="">Select Department</option>
								<?php
								// Fetch all departments for new course
								$departments_query = "SELECT department_id, department_code FROM departments";
								$departments_result = mysqli_query($con, $departments_query);

								while ($department = mysqli_fetch_assoc($departments_result)) {
									echo "<option value='" . $department['department_id'] . "'>" . $department['department_code'] . "</option>";
								}
								?>
							</select>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="cancel-btn" data-dismiss="modal">Close</button>
						<button type="submit" name="submit" class="save-btn">Add Course</button>
					</div>
				</form>
			</div>
		</div>
	</div>

	<script type="text/javascript" src="../../../frontend/layout/app.js" defer></script>
	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>