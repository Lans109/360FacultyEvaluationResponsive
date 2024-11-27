<script type="text/javascript" src="../../../frontend/layout/confirmation.js" defer></script>

<!-- Confirmation Modal for editing -->
<div class="editConfirmationModal" id="editConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="editConfirmationModalTitle" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="text-content">
                <div class="modal-header">
                    <span class="close" class="cancel-btn" id="closeEditButton">
                        <img src="../../../frontend/assets/icons/close2.svg" alt="Delete">
                    </span>                                            
                </div>
                <div class="modal-body">
                    <img src="../../../frontend/assets/icons/danger.svg" alt="Close">
                    <h3>Save Changes?</h3>
                    <p>Are you sure you want to save these changes? This action will overwrite the current data.</p>
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
<div class="deleteConfirmationModal" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="DeleteConfirmationModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content"> 
            <div class="text-content">
                <div class="modal-header">
                    <span class="close" class="cancel-btn" id="closeEditButton">
                        <img src="../../../frontend/assets/icons/close2.svg" alt="Delete">
                    </span>                                            
                </div>
                <div class="modal-body">
                    <img src="../../../frontend/assets/icons/close3.svg" alt="Close">
                    <h3>Are you sure?</h3>
                    <p>Do you really want to delete these records? This process cannot be undone.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="cancel-btn" id="cancelDeleteButton" data-dismiss="modal">Cancel</button>
                    <button type="button" class="save-btn" id="confirmDeleteButton">Yes, Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>
