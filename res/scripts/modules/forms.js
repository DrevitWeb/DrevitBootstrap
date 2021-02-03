function registerForm(id, func, callback, formId="form")
{
    $(document).on("submit.registered", "#"+id, function (e) {
        e.preventDefault();
        let form = document.querySelector("form#"+id)
        let formData = new FormData(form);

        ajaxRequest("POST", "./class/controllers/formsController.php?func="+func, function (data) {
            $(document).off("submit.registered", "#"+id);
            callback(data);
        }, formData, formId);
    })
}