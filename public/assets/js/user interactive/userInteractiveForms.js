/* THIS FUNCTION IS TO CHECK EACH OF THE REQUIRED FIELDS IF THEY ARE FILLED 
           IN ORDER TO ENABLE THE SAVE BUTTON
        */
function validateForm(moduleForm, classFormSubmit) {
    let isValid = true;

    // Check if all required fields have values
    $(moduleForm + " input[required]").each(function () {
        if ($(this).val() === "") {
            console.log($(this).attr("name"));
            isValid = false;
        }
    });

    $(classFormSubmit).prop("disabled", !isValid);
    console.log(
        "Submit button disabled: " + $(classFormSubmit).prop("disabled")
    );
}
