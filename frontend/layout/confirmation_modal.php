<script type="text/javascript" src="../../../frontend/layout/confirmation.js" defer></script>
<!-- Confirmation Modal for editing -->
<div class="editConfirmationModal" id="editConfirmationModal" tabindex="-1" role="dialog"
    aria-labelledby="editConfirmationModalTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-icon">
                <img src="../../../frontend/assets/icons/edit.svg">
            </div>
            <div class="text-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editConfirmationModalTitle">Confirm Changes</h5>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to save the changes?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="cancel-btn" id="cancelEditButton" data-dismiss="modal">Cancel</button>
                    <button type="button" class="save-btn" id="confirmEditButton">Yes, Save Changes</button>
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Modal for Delete Confirmation -->
<div class="deleteConfirmationModal" id="deleteConfirmationModal" tabindex="-1" role="dialog"
    aria-labelledby="DeleteConfirmationModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-icon">
                <img src="../../../frontend/assets/icons/monitor.svg">
            </div>
            <div class="text-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="DeleteConfirmationModal">Confirm Deletion</h5>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to delete this item? This action cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="cancel-btn" id="cancelDeleteButton"
                        data-dismiss="modal">Cancel</button>
                    <button type="button" class="save-btn" id="confirmDeleteButton">Yes, Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>
