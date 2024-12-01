<?php include '../modals.php' ?>
<nav>
    <div class="nav-items">
        <a href="program_chair_dashboard.php">
            <img src="../../frontend/assets/icons/department.svg" alt="Department" class="nav-icon"> <!-- Image for small screen -->
            <span class="nav-text">Department</span> <!-- Text for larger screens -->
        </a>
        <a href="../userprofile.php">
            <img src="../../frontend/assets/icons/account.svg" alt="Profile" class="nav-icon"> <!-- Image for small screen -->
            <span class="nav-text">Profile</span> <!-- Text for larger screens -->
        </a>
        <a href="program_chair_evaluation.php">
            <img src="../../frontend/assets/icons/evaluation.svg" alt="Evaluate" class="nav-icon"> <!-- Image for small screen -->
            <span class="nav-text">Evaluate</span> <!-- Text for larger screens -->
        </a>
        <a onclick="showLogoutModal()">
            <img src="../../frontend/assets/icons/logout.svg" alt="Logout" class="nav-icon"> <!-- Image for small screen -->
            <span class="nav-text">Logout</span> <!-- Text for larger screens -->
        </a>
        <span class="active-indicator"></span>
    </div>
</nav>

<!-- logout script -->
<script>
    // Show the logout modal
    function showLogoutModal() {
        // Hide other modals (if any) before showing the logout modal
        var otherModals = document.querySelectorAll('.modal');
        otherModals.forEach(function(modal) {
            modal.style.display = 'none';
        });

        // Show the logout modal
        document.getElementById('logoutModal').style.display = 'block';
    }

    function closeModal() {
        document.getElementById('logoutModal').style.display = 'none';
    }

    // Redirect to logout page
    function logout() {
        window.location.href = '../../logout.php'; // Redirect to logout page
    }

    // Close the modal when clicking outside of it
    window.onclick = function(event) {
        var modal = document.getElementById('logoutModal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
</script>

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