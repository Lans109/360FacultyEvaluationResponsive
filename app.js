const sidebar = document.getElementById('sidebar')

function toggleSidebar() {
    sidebar.classList.toggle('show')
}

// Reset sidebar visibility on screen resize
window.addEventListener("resize", () => {
    const sidebar = document.getElementById("sidebar");

    if (window.innerWidth < 1100) {
        sidebar.classList.remove("show"); // Hide sidebar on smaller screens
    } else {
        sidebar.classList.add("show"); // Show sidebar on larger screens
    }
});

// sort function
function sortCourses() {
    const sortValue = document.getElementById('sort').value;
    // Add sorting logic here 
}

// sort function
function sortPrograms() {
    const sortValue = document.getElementById('sort').value;
    // Add sorting logic here 
}

// modal 
function openModal(modalId) {
    document.getElementById(modalId).style.display = "block";
}

function closeModal(modalId) {
    document.getElementById(modalId).style.display = "none";
}

// Optional: Close the modal when clicking outside of it
window.onclick = function (event) {
    const modal = document.getElementById("myModal");
    if (event.target == modal) {
        closeModal();
    }
}






