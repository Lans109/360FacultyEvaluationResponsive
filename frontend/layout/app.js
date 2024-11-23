const sidebar = document.getElementById("sidebar");

function toggleSidebar() {
  sidebar.classList.toggle("show");
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

// Confirmation on Edits
document.addEventListener("DOMContentLoaded", function () {
  // Select all forms and dynamically assign event listeners
  const forms = document.querySelectorAll('form[id^="editForm"]'); // Select forms with IDs starting with "editForm"

  forms.forEach((form) => {
    // Find associated modal and buttons
    const formId = form.id.replace("editForm", ""); // Extract the unique ID
    const editModal = document.getElementById(`editModal${formId}`);
    const editConfirmationModal = document.getElementById(
      "editConfirmationModal"
    );
    const confirmEditButton = document.getElementById("confirmEditButton");

    // Add submit event listener to the form
    form.addEventListener("submit", function (event) {
      event.preventDefault(); // Prevent default form submission
      editModal.style.display = "none";
      editConfirmationModal.style.display = "block"; // Show confirmation modal

      // Handle confirmation
      confirmEditButton.onclick = function () {
        editConfirmationModal.style.display = "none"; // Hide the modal
        form.submit(); // Submit the form programmatically
      };

      //Handles cancel and will bring back to edit modal
      cancelEditButton.onclick = function () {
        editConfirmationModal.style.display = "none"; // Hide the modal
        editModal.style.display = "block";
      };
    });

    // Close modal when clicking outside or on cancel button
    const cancelButtons = editConfirmationModal.querySelectorAll(
      ".cancel-btn, .close"
    );
    cancelButtons.forEach((btn) => {
      btn.addEventListener("click", function () {
        confirmationModal.style.display = "none";
      });
    });
  });
});

// COnfirmation on deletes
let deleteHref = ""; // Variable to store the delete link dynamically

function openDeleteConfirmationModal(event, element) {
  event.preventDefault(); // Prevent the default link behavior
  deleteHref = element.getAttribute("href"); // Store the href of the clicked button
  const deleteModal = document.getElementById("deleteConfirmationModal");
  deleteModal.style.display = "block"; // Show the modal
}

document.addEventListener("DOMContentLoaded", function () {
  const deleteModal = document.getElementById("deleteConfirmationModal");
  const confirmDeleteButton = document.getElementById("confirmDeleteButton");
  const cancelDeleteButton = document.getElementById("cancelDeleteButton");

  // Confirm deletion: Navigate to the stored href
  confirmDeleteButton.addEventListener("click", function () {
    window.location.href = deleteHref; // Redirect to the stored href
  });

  // Cancel deletion: Hide the modal
  cancelDeleteButton.addEventListener("click", function () {
    deleteModal.style.display = "none";
  });

  // Optional: Close modal when clicking outside the content
  deleteModal.addEventListener("click", function (event) {
    if (event.target === deleteModal) {
      deleteModal.style.display = "none";
    }
  });
});
