// Confirmation on Edits
document.addEventListener("DOMContentLoaded", function () {
  // Select all forms with name="editForm"
  const forms = document.querySelectorAll('form[name="editForm"]'); // Select all forms with the name "editForm"

  forms.forEach((form) => {
    // Extract the unique ID from the form's name (e.g., form name: editForm{faculty_id})
    const formName = form.getAttribute('name');  // Get the form's name
    const modalId = `editModal${formName.replace('editForm', '')}`; // Construct the modal ID (assumes form name is "editForm{ID}")
    const editModal = document.getElementById(modalId); // Get the corresponding modal by ID
    const editConfirmationModal = document.getElementById("editConfirmationModal");
    const confirmEditButton = document.getElementById("confirmEditButton");
    const cancelEditButton = document.getElementById("cancelEditButton");

    // Add submit event listener to the form
    form.addEventListener("submit", function (event) {
      event.preventDefault(); // Prevent default form submission
      editModal.style.display = "none"; // Hide the edit modal
      editConfirmationModal.style.display = "block"; // Show the confirmation modal

      // Handle confirmation (submit the form when confirmed)
      confirmEditButton.onclick = function () {
        editConfirmationModal.style.display = "none"; // Hide the confirmation modal
        form.submit(); // Submit the form programmatically
      };

      // Handle cancel (bring back the edit modal)
      cancelEditButton.onclick = function () {
        editConfirmationModal.style.display = "none"; // Hide the confirmation modal
        editModal.style.display = "block"; // Show the edit modal
      };
    });

    // Close modal when clicking outside or on cancel button
    const cancelButtons = editConfirmationModal.querySelectorAll(".cancel-btn, .close");
    cancelButtons.forEach((btn) => {
      btn.addEventListener("click", function () {
        editConfirmationModal.style.display = "none"; // Hide the confirmation modal
      });
    });
  });

  // Confirmation on Deletes
  let deleteHref = ""; // Variable to store the delete link dynamically

  // Function to open the delete confirmation modal
  function openDeleteConfirmationModal(event, element) {
    event.preventDefault(); // Prevent the default link behavior
    deleteHref = element.getAttribute("href"); // Store the href of the clicked button
    const deleteModal = document.getElementById("deleteConfirmationModal");
    deleteModal.style.display = "block"; // Show the delete confirmation modal
  }

  // Add event listener for delete modal interactions
  const deleteModal = document.getElementById("deleteConfirmationModal");
  const confirmDeleteButton = document.getElementById("confirmDeleteButton");
  const cancelDeleteButton = document.getElementById("cancelDeleteButton");

  // Confirm deletion: Navigate to the stored href
  confirmDeleteButton.addEventListener("click", function () {
    window.location.href = deleteHref; // Redirect to the stored href
  });

  // Cancel deletion: Hide the modal
  cancelDeleteButton.addEventListener("click", function () {
    deleteModal.style.display = "none"; // Hide the delete confirmation modal
  });

  // Optional: Close modal when clicking outside the content
  deleteModal.addEventListener("click", function (event) {
    if (event.target === deleteModal) {
      deleteModal.style.display = "none"; // Hide modal if clicked outside
    }
  });

  // Expose the openDeleteConfirmationModal function for external use
  window.openDeleteConfirmationModal = openDeleteConfirmationModal;
});
