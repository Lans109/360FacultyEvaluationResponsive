<nav>
    <div class="nav-items">
        <a href="faculty_dashboard.php">Courses Handled</a>
        <a href="../userprofile.php">Profile</a>
        <a href="faculty_evaluation.php">Evaluate</a>
        <a href="../../logout.php" onclick="return confirm('Are you sure you want to logout?')">Logout</a>
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