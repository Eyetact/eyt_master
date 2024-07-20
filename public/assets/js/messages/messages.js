document.addEventListener("DOMContentLoaded", function () {
    var alertMessage = document.getElementById("alertMessage");
    if (alertMessage) {
        setTimeout(function () {
            alertMessage.style.opacity = "0";
            setTimeout(function () {
                alertMessage.remove();
            }, 500); // match the transition duration
        }, 2000);
    }
});

function showAlert(message) {
    $("#alertMessage").text(message);
    $("#alertMessage").fadeIn();

    setTimeout(function () {
        $("#alertMessage").fadeOut();
    }, 5000); // Hide after 5 seconds
}

function hideAlert() {
    $("#alertMessage").fadeOut();
}

// Example usage: Call showAlert with a message when needed
$(document).ready(function () {
    // Simulate a successful operation
    var successMessage = "{{ session('message') }}";
    if (successMessage) {
        showAlert(successMessage);
    }
});
