function viewFile(element) {
  var id = element.value;
  var modalBody = document.getElementById('modalBodyContent');
  modalBody.innerHTML = 'Loading...';
  fetch('../config/for_review.php?getFile=true&id=' + id)
    .then(response => response.json())
    .then(data => {
      var filePath = data.filePath;
      var fileType = data.fileType;
      var allowedExtensions = [
        'pdf', 'jpg', 'jpeg', 'png', 'gif', 'bmp',
        'tiff', 'tif', 'webp', 'svg', 'heif', 'heic',
        'PDF', 'JPG', 'JPEG', 'PNG', 'GIF', 'BMP',
        'TIFF', 'TIF', 'WEBP', 'SVG', 'HEIF', 'HEIC'
      ];

      if (allowedExtensions.includes(fileType)) {
        modalBody.innerHTML = '<iframe src="' + filePath + '" style="width:100%; height:500px;" frameborder="0"></iframe>';
      } else {
        fetch('../config/for_review.php?loadFile=true&file=' + filePath)
          .then(response => response.text())
          .then(data => {
            modalBody.innerHTML = data;
          });
      }
    });
  openSpecificModal('docModal', 'modal-xl');
}