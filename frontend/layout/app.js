
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

  const jsConfetti = new JSConfetti()

  window.addEventListener('load', () => {
    jsConfetti.addConfetti({
      confettiColors: [
        "#923534",  // Base Maroon
        "#C04747",  // Vibrant Red-Maroony
        "#FF6868",  // Bright Coral Red
        "#F2D3C4",  // Peach Beige
        "#FFD27F",  // Warm Gold
        "#FFB3B3"   // Playful Blush Pink
      ],
      confettiRadius: 6,
      confettiNumber: 200,
    })
  })

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

window.addEventListener('load', function () {
  // Hide the loader after the page has fully loaded
  document.getElementById('loader').style.display = 'none';
  document.getElementById('content-wrapper').style.display = 'block';
});


// Get the slider and input elements
const studentSlider = document.getElementById('student_slider');
const studentInput = document.getElementById('student_scoring');

const chairSlider = document.getElementById('chair_slider');
const chairInput = document.getElementById('chair_scoring');

const peerSlider = document.getElementById('peer_slider');
const peerInput = document.getElementById('peer_scoring');

const selfSlider = document.getElementById('self_slider');
const selfInput = document.getElementById('self_scoring');

// Function to update the input value when the slider is changed
function updateInputValue(slider, input) {
    input.value = slider.value;
}

// Function to update the slider value when the input is changed
function updateSliderValue(input, slider) {
    let value = parseInt(input.value, 10);
    if (isNaN(value)) {
        value = 0; // Default to 0 if the input is invalid
    } else if (value < 0) {
        value = 0; // Ensure the value is not below 0
    } else if (value > 100) {
        value = 100; // Ensure the value is not above 100
    }
    slider.value = value;
}

// Event listeners for slider changes
studentSlider.addEventListener('input', function () {
    updateInputValue(studentSlider, studentInput);
});

chairSlider.addEventListener('input', function () {
    updateInputValue(chairSlider, chairInput);
});

peerSlider.addEventListener('input', function () {
    updateInputValue(peerSlider, peerInput);
});

selfSlider.addEventListener('input', function () {
    updateInputValue(selfSlider, selfInput);
});

// Event listeners for input changes
studentInput.addEventListener('input', function () {
    updateSliderValue(studentInput, studentSlider);
});

chairInput.addEventListener('input', function () {
    updateSliderValue(chairInput, chairSlider);
});

peerInput.addEventListener('input', function () {
    updateSliderValue(peerInput, peerSlider);
});

selfInput.addEventListener('input', function () {
    updateSliderValue(selfInput, selfSlider);
});

