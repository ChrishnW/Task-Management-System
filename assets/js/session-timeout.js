let inactivityTimeout; // Tracks inactivity timeout
let countdownTimer; // Tracks the countdown timer inside the modal
let timeRemaining = 3600; // 5 minutes in seconds
let endTime;

// Function to format time in MM:SS
function formatTime(seconds) {
  const minutes = Math.floor(seconds / 60);
  const secs = seconds % 60;
  return `${minutes}m ${secs.toString().padStart(2, '0')}s`;
}

// Function to start the countdown in the modal
function startCountdown() {
  const countdownElement = document.getElementById('timeoutCountdown');
  endTime = new Date().getTime() + timeRemaining * 1000; // Calculate the end time

  countdownTimer = setInterval(() => {
    const currentTime = new Date().getTime();
    timeRemaining = Math.max(0, Math.floor((endTime - currentTime) / 1000)); // Calculate remaining time in seconds

    countdownElement.textContent = formatTime(timeRemaining);

    if (timeRemaining <= 0) {
      clearInterval(countdownTimer);
      window.location.href = '../include/logout.php';
    }
  }, 1000);
}

// Reset inactivity timer on user activity
function resetInactivityTimer() {
  clearTimeout(inactivityTimeout);
  inactivityTimeout = setTimeout(() => {
    showTimeoutModal();
  }, 300000); // 5 minutes of inactivity
}

// Show the modal
function showTimeoutModal() {
  $('#timeoutModal').modal('show');
  startCountdown();
}

// Button actions inside the modal
document.getElementById('continueBtn').addEventListener('click', () => {
  clearInterval(countdownTimer);
  $('#timeoutModal').modal('hide');
  resetInactivityTimer(); // Restart inactivity timer
});

document.getElementById('logoutBtn').addEventListener('click', () => {
  clearInterval(countdownTimer);
  window.location.href = '../include/logout.php';
  // Add your logout functionality here
});

// Start inactivity timer on page load
resetInactivityTimer();

// Event listeners for activity
['mousemove', 'keydown', 'click', 'scroll', 'touchstart'].forEach(event => {
  document.addEventListener(event, resetInactivityTimer);
});