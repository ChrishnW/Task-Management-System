let inactivityTimeout; // Tracks inactivity timeout (3 minutes)
let countdownTimer; // Tracks the countdown timer inside the modal (60 minutes)
let countdownTimeRemaining = 3600; // Countdown starts at 60 minutes (3600 seconds)
const inactivityThreshold = 10000; // 3 minutes of inactivity in milliseconds
let isCountdownActive = false; // Flag to track if the countdown modal is active

// Function to format time in MM:SS
function formatTime(seconds) {
  const minutes = Math.floor(seconds / 60);
  const secs = seconds % 60;
  return `${minutes}m ${secs.toString().padStart(2, '0')}s`;
}

// Function to start the countdown in the modal
function startCountdown() {
  const countdownElement = document.getElementById('timeoutCountdown');
  const countdownEndTime = new Date().getTime() + countdownTimeRemaining * 1000;

  // Clear any existing countdown timer
  clearInterval(countdownTimer);

  countdownTimer = setInterval(() => {
    const currentTime = new Date().getTime();
    countdownTimeRemaining = Math.max(0, Math.floor((countdownEndTime - currentTime) / 1000));

    countdownElement.textContent = formatTime(countdownTimeRemaining);

    if (countdownTimeRemaining <= 0) {
      clearInterval(countdownTimer);
      isCountdownActive = false; // Reset flag
      window.location.href = '../include/logout.php'; // Logout when timer reaches 0
    }
  }, 1000);
}

// Reset the inactivity timer
function resetInactivityTimer() {
  if (isCountdownActive) {
    // Ignore user activity while the countdown modal is active
    return;
  }

  clearTimeout(inactivityTimeout);
  inactivityTimeout = setTimeout(() => {
    showTimeoutModal();
  }, inactivityThreshold); // 3 minutes of inactivity
}

// Show the modal and start the countdown
function showTimeoutModal() {
  isCountdownActive = true; // Countdown starts, ignore user activity
  $('#timeoutModal').modal('show');
  countdownTimeRemaining = 3600; // Reset countdown to 60 minutes
  startCountdown();
}

// Event listener for "Continue" button
document.getElementById('continueBtn').addEventListener('click', () => {
  clearInterval(countdownTimer); // Stop the current countdown
  countdownTimeRemaining = 3600; // Reset countdown to 60 minutes
  isCountdownActive = false; // Re-enable user activity detection
  $('#timeoutModal').modal('hide'); // Hide the modal
  resetInactivityTimer(); // Restart the inactivity timer
});

// Event listener for "Logout" button
document.getElementById('logoutBtn').addEventListener('click', () => {
  clearInterval(countdownTimer); // Stop the countdown
  isCountdownActive = false; // Reset flag
  window.location.href = '../include/logout.php'; // Perform logout
});

// Initialize the inactivity timer on page load
resetInactivityTimer();

// Add event listeners for user activity
['mousemove', 'keydown', 'click', 'scroll', 'touchstart'].forEach(event => {
  document.addEventListener(event, resetInactivityTimer);
});