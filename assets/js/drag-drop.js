$('#finish').on('shown.bs.modal', function () {
  var fileDropArea = $('#fileDropArea');
  var fileInput = $('#file-1');
  var fileList = $('#fileList');
  var dt = new DataTransfer();

  fileDropArea.on('dragover', function (e) {
    e.preventDefault();
    e.stopPropagation();
    $(this).addClass('dragover');
  });

  fileDropArea.on('dragleave', function (e) {
    e.preventDefault();
    e.stopPropagation();
    $(this).removeClass('dragover');
  });

  fileDropArea.on('drop', function (e) {
    e.preventDefault();
    e.stopPropagation();
    $(this).removeClass('dragover');
    var files = e.originalEvent.dataTransfer.files;
    handleFiles(files);
  });

  fileDropArea.on('click', function () {
    fileInput.click();
  });

  fileInput.on('change', function () {
    var files = $(this)[0].files;
    handleFiles(files);
  });

  function handleFiles(files) {
    for (var i = 0; i < files.length; i++) {
      dt.items.add(files[i]);
      var fileItem = $('<div class="file-item"></div>');
      fileItem.append('<span>' + files[i].name + '</span>');
      var removeButton = $('<button type="button">Remove</button>');
      removeButton.on('click', function () {
        var index = $(this).parent().index();
        dt.items.remove(index);
        fileInput[0].files = dt.files;
        $(this).parent().remove();
      });
      fileItem.append(removeButton);
      fileList.append(fileItem);
    }
    fileInput[0].files = dt.files;
  }
});