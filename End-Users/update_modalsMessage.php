<!-- Success Modal -->
<div id="successModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <img src='../frontend/assets/icons/success.svg' alt="Success">
            <h4>Success</h4>
        </div>
        <p id="successMessage"></p>
        <div class="modal-footer">
            <button class="btn-change" onclick="closeMessageModal('successModal')">Close</button>
        </div>
    </div>
</div>

</div>
<!-- Error Modal -->
<div id="errorModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <img src='../frontend/assets/icons/error.svg' alt="Error">
            <h4>Error</h4>
        </div>
        <p id="errorMessage"></p>
        <div class="modal-footer">
            <button onclick="closeMessageModal('errorModal')">Close</button>
        </div>
    </div>
</div>