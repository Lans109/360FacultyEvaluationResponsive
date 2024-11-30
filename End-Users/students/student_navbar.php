<nav>
    <div class="nav-items">
        <a href="student_dashboard.php">
            <img src="../../frontend/assets/icons/course.svg" alt="Courses" class="nav-icon"> <!-- Image for small screen -->
            <span class="nav-text">Courses</span> <!-- Text for larger screens -->
        </a>
        <a href="../userprofile.php">
            <img src="../../frontend/assets/icons/account.svg" alt="Profile" class="nav-icon"> <!-- Image for small screen -->
            <span class="nav-text">Profile</span> <!-- Text for larger screens -->
        </a>
        <a href="student_evaluation.php">
            <img src="../../frontend/assets/icons/evaluation.svg" alt="Evaluate" class="nav-icon"> <!-- Image for small screen -->
            <span class="nav-text">Evaluate</span> <!-- Text for larger screens -->
        </a>
        <a href="../../logout.php" onclick="return confirm('Are you sure you want to logout?')">
            <img src="../../frontend/assets/icons/logout.svg" alt="Logout" class="nav-icon"> <!-- Image for small screen -->
            <span class="nav-text">Logout</span> <!-- Text for larger screens -->
        </a>
        <span class="active-indicator"></span>
    </div>
</nav>


<script>
    document.addEventListener("DOMContentLoaded", function () {
        const links = document.querySelectorAll('.nav-items a');

        // Set the active class based on the current page
        const currentPage = window.location.pathname.split('/').pop();
        links.forEach(link => {
            if (link.href.includes(currentPage)) {
                link.classList.add('active');
            }
        });

        // Add event listener to animate the active indicator on click
        links.forEach(link => {
            link.addEventListener('click', function () {
                // Remove the active class from all links
                links.forEach(l => l.classList.remove('active'));

                // Add the active class to the clicked link
                link.classList.add('active');
            });
        });
    });
</script>