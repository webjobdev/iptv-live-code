function readAsUrl(input) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function (e) {
      document.getElementById('image').src = e.target.result;
    };
    reader.onloadend = function (e) {
      $('#modal').modal('show');
    };
    reader.readAsDataURL(input.files[0]);
  }
};
$(document).ready(function () {
  var image = document.getElementById('image');
  $(document).on('change', '.uploadImg', function (e) {
    var angularScope =$(this).scope();
    var ValidImageTypes  = ["image/gif", "image/jpeg", "image/png"];
    var files = e.target.files;
    var fileType = files[0].type;
    if ($.inArray(fileType, ValidImageTypes) < 0) {
        angularScope.errors['thumbnail'] = {has : true , message :'Invalid file format. Upload only jpeg and png file formats.'};
        angularScope.$apply();
      return;
    }else{
      angularScope.errors['thumbnail'] = '';
      angularScope.$apply();
    }
    $('.crop-body').show();
    readAsUrl(this);
  });
  var cropBoxData;
  var canvasData;
  var cropper;
  $(document).on('show.bs.modal', '#modal', function () {
    $('.error_msg').hide();
    setTimeout(function () {
      cropper = new Cropper(image, {
        autoCropArea: 1,
        viewMode: 3,
        aspectRatio: 40 / 43,
        preview: '.img-preview',
        cropBoxResizable: false,
        minCropBoxWidth: 200,
        minCropBoxHeight: 245,
        dragCrop: false,
        mouseWheelZoom: false,
        resizable: false,
        ready: function () {
          //Should set crop box data first here
          cropper.setCropBoxData(cropBoxData).setCanvasData(canvasData);
        }
      });
    }, 500);
  });
  $(document).on('hidden.bs.modal', '#modal', function () {
    document.getElementsByClassName("uploadImg")[0].value = "";
    $('#submit-image').prop('disabled', false);
    cropper.destroy();
  });
  $(document).on('click', '#submit-image', function () {
    cropBoxData = cropper.getCropBoxData();
    canvasData = cropper.getCroppedCanvas().toBlob(function (blob) {
      var module = document.getElementById('module').value;
      var size = document.getElementById('size').value;
      var formData = new FormData();
      formData.append('module', module);
      formData.append('size', size);
      formData.append('image', blob);
      $('.crop-body').hide();
      $('.loader-container').show();
      $('#submit-image').prop('disabled', true);
      $.ajax($('meta[name="base-api-url"]').attr('content') + '/audio-base/thumbnail', {
        method: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success(data) {
          $('.uploaded_img').attr('src', data.info);
          $('.uploaded_img').show();
          $('#isImgUpdated').val(1);
          $('.loader-container').hide();
          $('#modal').modal('hide');
        },
        error() {
          $('.loader-container').hide();
          $('.error_msg').show().text("Please upload bigger image");
        },
      })
    }, 'image/jpeg');
  });
})