<?php  ?>
<div class="success-modal" id="successModal" tabindex="-1" role="dialog" aria-labelledby="successModalLabel" aria-hidden="true" style="display: block;">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="text-content">
                    <div class="modal-body">
                        <!-- Conditionally change image and message based on status -->
                        <?php if ($status == 'success'): ?>
                            <img src="../../../frontend/assets/icons/success.svg" alt="Success">
                            <h3>Success</h3>
                        <?php elseif ($status == 'error'): ?>
                            <img src="../../../frontend/assets/icons/error.svg" alt="Error">
                            <h3>Error</h3>
                        <?php endif; ?>
                            <p><?= $message ?></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cancel-btn" id="doneButton" onclick="closeModal()">Done</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script>
    // Function to close the modal, now defined globally
    function closeModal() {
        const modal = document.getElementById("successModal");
        modal.style.display = "none"; // Hides the modal
    }

</script>
