const dropZone = document.getElementById('dropZone');
const fileInput = document.getElementById('fileInput');
const fileNameDisplay = document.getElementById('fileName');
const importBtn = document.getElementById('importBtn');
const progressBar = document.getElementById('progressBar');
const progressContainer = document.getElementById('progressContainer');
const loadingIcon = document.getElementById('loadingIcon');
const validateIcon = document.getElementById('validateIcon');
const errorIcon = document.getElementById('errorIcon');
let selectedFile;

// Handle drag-and-drop and file input selection
dropZone.addEventListener('dragover', (e) => e.preventDefault());
dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));
dropZone.addEventListener('drop', (e) => {
  e.preventDefault();
  const files = e.dataTransfer.files;
  if (files.length > 0) handleFileSelection(files[0]);
});
fileInput.addEventListener('change', (e) => {
  const files = e.target.files;
  if (files.length > 0) handleFileSelection(files[0]);
});

// Display the selected file and trigger AJAX validation
function handleFileSelection(file) {
  if (file && (file.type === "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" || file.type === "application/vnd.ms-excel")) {
    selectedFile = file;
    fileNameDisplay.textContent = `Selected file: ${file.name}`;

    // Hide drop zone and show progress bar and loading icon
    dropZone.classList.add('d-none');
    progressContainer.classList.remove('d-none');
    loadingIcon.classList.remove('d-none');

    validateFile(); // Start validation
  } else {
    fileNameDisplay.textContent = "Please select a valid Excel file.";
  }
}