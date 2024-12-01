<!-- Success Modal -->
<div id="successModal" class="modal" style="display: none;">
    <div class="modal-content">
        <div class="modal-header">
            <img src='Styles/success.svg' alt="Success" width="90px">
            <h4>Success</h4>
        </div>
        <p id="successMessage" style="text-align: center;"></p>
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
            <img src='Styles/error.svg' alt="Error" width="90px">
            <h4>Error</h4>
        </div>
        <p id="errorMessage" style="text-align: center;"></p>
        <div class="modal-footer">
            <button onclick="closeMessageModal('errorModal')">Close</button>
        </div>
    </div>
</div>