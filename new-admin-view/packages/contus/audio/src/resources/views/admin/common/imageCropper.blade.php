<div class="custom-modal fade" id="modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="custom-modal-dialog img-cropper" role="document">
        <div class="custom-modal-content">
            <div class="custom-modal-header">
                Crop Image                
            </div>
            <div class="custom-modal-body">
                <div class="loader-container">
                    <img src="{{asset('adminview/assets/images/loader.gif')}}">
                </div>
                <p class="error_msg"></p>
                <div class="crop-body">
                    <div class="img-container">
                        <img id="image" src="" alt="Picture">
                    </div>
                    <div class="img-preview"></div>
                </div>
            </div>
            <div class="custom-modal-footer text-right">
                <input type="hidden" class="audio-index" name="audio-index" value="" />
                <button type="button" class="popup-button grey-color" data-dismiss="modal">Close</button>
                <button type="button" class="popup-button blue-color" id="submit-image">Submit</button>                
            </div>
        </div>
    </div>
</div>