function ajaxRequest(type, request, callback, data = null, formId="form", errorCallback = null, async = true)
{
    let xhr;
    // Create XML HTTP request.
    xhr = new XMLHttpRequest();
    if (type === 'GET' && data != null) {request += '?' + data;}

    xhr.open(type, request, async);
    if(!(data instanceof FormData)) {xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');}

    // Add the onload function.
    xhr.onload = function ()
    {
        switch (xhr.status) {
            case 200:
            case 201:
                callback(xhr.responseText);
                break;
            case 400:
                $("#"+formId).html(xhr.responseText);
                break;
            default:
                errorCallback(data, xhr.statusText);
        }
    };
    // Send XML HTTP request.
    xhr.send(data);
}

function ajaxError(code)
{
    if(code === 401) {$("#errors").append($("<div class='alert alert-error'>Vous n'êtes pas autorisé à effectuer cela.</div><br/>"));}
    else if(code === 401) {$("#errors").append($("<div class='alert alert-error'>Ressource non trouvée</div><br/>"));}
    else if(code === 500) {$("#errors").append($("<div class='alert alert-error'>Erreur interne au serveur</div><br/>"));}
    else {$("#errors").append($("<div class='alert alert-error'>Erreur fatale</div><br/>"));}
}