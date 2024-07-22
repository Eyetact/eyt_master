/**
 * THIS JS FILE CONTAINS THE COMMON FUNCTIONS NEEDED WHEN SUBMIT FORMS
 * */

/**@argument formType: #formModel | admin..
 * @argument response : the acutal returned data
 * @argument resultType: success | danger..
 * @argument timesout in mellisecond
 *
 * THIS FUNCTION I TO MANAGE RESPONDING TO THE FORM
 * 1. IT HIDES THE POP UP FORM
 * 2. MANAGES THE MESSAGE (SUCCESS & DANGER) WITHIN A SPECIFIC TIME OF APPEARANCE
 * 3. INCREASE THE COUNTER OF MODULE NUMBER
 */
function manageMessageResponse(formType, response, resultType, timeout) {
    // 1. Hide the pop-up form
    $("#" + formType).modal("hide");

    // 2. If the message is success
    if (resultType === "success") {
        // Display the success or danger message
        toastr.success(response.message, "Success");
    } else toastr.error(response.message, "Error");
}

// Function to display the error messages corresponding to each input field in the form
function displayValidationErrorsFields(errors, formType) {
    $(".error-message").text("");

    $.each(errors, function (key, value) {
        var errorSpan = $("#" + key + "-error");
        if (errorSpan.length) {
            errorSpan.html(value[0]);
            errorSpan.removeClass("d-none");
        }
    });
}
