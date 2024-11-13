const sidebar = document.getElementById('sidebar')

function toggleSidebar(){
    sidebar.classList.toggle('show')
}

// Reset sidebar visibility on screen resize
window.addEventListener("resize", () => {
    const sidebar = document.getElementById("sidebar");

    if (window.innerWidth < 800) {
        sidebar.classList.remove("show"); // Hide sidebar on smaller screens
    } else {
        sidebar.classList.add("show"); // Show sidebar on larger screens
    }
});