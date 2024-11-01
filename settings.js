jQuery(function ($) {
    // recaptcha
    $.getScript("https://www.google.com/recaptcha/api.js")
    // submit
    var $registrationForm = $("#registrationForm")
    var $registrationFormSubmit = $("#registrationFormSubmit")
    var $registrationFormSuccess = $("#registrationFormSuccess")
    var $registrationFormError = $("#registrationFormError")
    var $optionsForm = $("#optionsForm")
    $registrationForm.on("submit", function (event) {
        $registrationFormSubmit.prop("disabled", true)
            .data("origingal-text", $registrationFormSubmit.text())
            .text("Working")
        event.preventDefault()
        grecaptcha.reset()
        grecaptcha.execute()
    })
    window.registrationFormSubmitCallback = function (captchaResult) {
        var paramsArray = $registrationForm.serializeArray()
        var params = {}
        $.each(paramsArray, function (index, param) {
            switch (param.name) {
                case "terms":
                    params[param.name] = param.value === "on"
                    break
                default:
                    params[param.name] = param.value
            }
        })
        $.ajax({
            method: "POST",
            url: 'https://tiny.pictures/wapi/user/',
            contentType: "application/json",
            data: JSON.stringify(params),
            success: function (data, textStatus) {
                var user = data.data
                $optionsForm.find("input[name=\"tinyPictures[user]\"]").val(user.name)
                $optionsForm.find("input[name=\"tinyPictures[source]\"]").val("main")
                $registrationFormSuccess.html("<p>Registration successful.</p><p>We prefilled the options form with your new user name and source. Please note that the SSL certificate setup might take some minutes to complete.</p><p><b>Save changes to activate the plugin.</b></p>")
                    .prop("hidden", false)
                $registrationForm.children().not(".notice").prop("hidden", true)
            },
            error: function (jqXHR, textStatus) {
                var data = JSON.parse(jqXHR.responseText)
                $registrationFormError.html("<p>" + data.error.message + "</p>").prop("hidden", false)
                $registrationFormSubmit.prop("disabled", false)
                    .text($registrationFormSubmit.data("original-text"))
            },
        })
    }
})
