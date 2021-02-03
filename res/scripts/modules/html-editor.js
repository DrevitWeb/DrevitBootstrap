/***********************************************************\
 $Variables d'usage
\***********************************************************/

let sizeFocused = false;
let sizeSelection;
let content = $("#content-edit");

/***********************************************************\
 $Paramètres de la page et de l'édition, au démarrage de l'app
\***********************************************************/

$(document).ready(function() {
    document.execCommand("styleWithCSS");
    document.execCommand("defaultParagraphSeparator", false, "div");
    let menuElement = $("#editor-actions li *");

    appendNewTabButton();

    $(menuElement).click(function (ev) {
        menu(ev);
    });

    $(menuElement).change(function (ev) {
        menu(ev);
    });
});

/***********************************************************\
 $Evénements listeners
\***********************************************************/

/*Lorsque l'on fait une selection de texte avec le curseur*/
$("#font-size").focus(function () {
    if(!sizeFocused)
    {
        sizeFocused = true;
        sizeSelection = saveSelection();
    }
});

/*Lorsque l'on lache une selection*/
$("#font-size").blur(function () {
    sizeFocused = false;
});

/*Lorsque la taille de texte est changée*/
$("#font-size").change(function () {
    restoreSelection(sizeSelection);
    let viewport = $( window ).width();
    document.execCommand("formatblock", false, "p");
    selectedElement = getSelection().anchorNode.parentElement;
    selectedElement.style.fontSize = ($("#font-size").val()/viewport*100)+'vw';
});

/*Lorsque l'on annule Ctrl+Z / Ctrl+Y*/
content.keydown(function(e){
    let zKey = 90;
    let yKey = 89;
    if ((e.ctrlKey || e.metaKey) && e.keyCode === zKey) {
        e.preventDefault();
        document.execCommand("undo");
        return false;
    }

    if ((e.ctrlKey || e.metaKey) && e.keyCode === yKey) {
        e.preventDefault();
        document.execCommand("redo");
        return false;
    }
});

/*Modification du menu lorsque l'on focus un texte*/
content.click(function (ev) {
    modifyTType(ev);
});

/*Modification du menu lorsque l'on focus un texte*/
content.keydown(function (ev) {
    modifyTType(ev);
});

/*
$(document).on("submit.registered", "#sendPage", function (e) {
    $("#formError").css("display", "none");
    e.preventDefault();
    let form = document.querySelector("form#sendPage");
    let formData = new FormData(form);

    let error = false;
    let errors = "";

    if(!$("#title").val() || $("#title").val() === "")
    {
        error = true;
        errors += "Le titre doit être renseigné<br/>"
    }

    if(!$("#page_id").val() || $("#page_id").val() === "")
    {
        error = true;
        errors += "L'identifiant doit être renseigné<br/>"
    }

    if(error)
    {
        $("#formError").css("display", "block");
        $("#formError").html("");
        $("#formError").html(errors);
        $(document).scrollTop = 0;
        return;
    }

    formatAll();

    formData.append("title", $("#title").val());
    formData.append("page_id", $("#page_id").val());
    formData.append("content", $("#content-edit").html());

    ajaxRequest("POST", "./class/controllers/formsController.php?func=sendPage", function (data) {
        //SUCCESS

        $(document).off("submit.registered", "#sendPage");
        window.location.reload();
    }, formData, "sendPage", function (error) {
        //ERROR

        $("#formError").html(error);
        $(document).scrollTop = 0;
    });
});
*/
/*function modifyPage(page_id)
{
    ajaxRequest("post", "class/controllers/pagesController.php?func=getPage", function (data) {
        //SUCCESS
        let page = JSON.parse(data);
        $("#formTitle").html("Modifier la page " + page.title);
        $("#sendPageBlock").remove();
        $("#modifyPageBlock").css("display", "block");
        $("#title").val(page.title);
        $("#page_id").val(page.page_id);
        $("#content-edit").html(page.content);

        $(document).on("submit.registered", "#modifyPage", function (e) {
            $("#formError").css("display", "none");
            e.preventDefault();
            let form = document.querySelector("form#modifyPage");
            let formData = new FormData(form);

            let error = false;
            let errors = "";

            if(!$("#title").val() || $("#title").val() === "")
            {
                error = true;
                errors += "Le titre doit être renseigné<br/>"
            }

            if(!$("#page_id").val() || $("#page_id").val() === "")
            {
                error = true;
                errors += "L'identifiant doit être renseigné<br/>"
            }

            if(error)
            {
                $("#formError").css("display", "block");
                $("#formError").html("");
                $("#formError").html(errors);
                $(document).scrollTop = 0;
                return;
            }

            formatAll();

            formData.append("title", $("#title").val());
            formData.append("page_id", $("#page_id").val());
            formData.append("content", $("#content-edit").html());
            formData.append("id", page.id);

            ajaxRequest("POST", "./class/controllers/formsController.php?func=modifyPage", function (data) {
                //SUCCESS

                $(document).off("submit.registered", "#modifyPage");
                window.location.reload();
            }, formData, "modifyPage", function (error) {
                //ERROR

                $("#formError").html(error);
                $(document).scrollTop = 0;
            });
        });

    }, "page_id="+page_id, "", function () {
        //ERROR
        window.location.reload();
    });
    reformatAll();
};*/

/*
Modifie la valeur du menu de type de texte
*/
function modifyTType(ev)
{
    let ttype = $("#text-type");
    switch (ev.target.nodeName) {
        case "H1":
            ttype.val("h1");
            break;
        case "H2":
            ttype.val("h2");
            break;
        case "H3":
            ttype.val("h3");
            break;
        case "H4":
            ttype.val("h4");
            break;
        case "H5":
            ttype.val("h5");
            break;
        default:
            ttype.val("p");
    }
}

/*Ajout d'un nouvel onglet à la page*/
function appendNewTab()
{
    let tab = $("<div class='tab'></div>");
    let tab_edit = $("<div class='tab_edit'>Add text here...</div>");
    let modifyButton = $("<i class='fas fa-ellipsis-v tabmenu' title='Options'></i>");
    tab_edit.attr("contenteditable", "true");
    modifyButton.attr("contenteditable", "false");

    tab_edit.focus(function (ev) {
        if(tab_edit.text() === "Add text here...")
        {
            $(ev.target).select();
        }
    })
    let menuState = false; //False = closed, True = opened

    modifyButton.click(function (ev) {

        if(!menuState)
        {
            let menu = buildMenu(tab);
            tab.prepend(menu);
            menuState = true;
        }
        else
        {
            $(ev.target.parentNode).find(".tab_menu").remove();
            menuState = false;
        }
    });


    tab.append(modifyButton);
    tab.append(tab_edit);
    content.append(tab);
}


/*Ajout d'une colonne dans un onglet*/
function addColumn(editable)
{
    let c1 = $("<div class='column' style=''></div>");
    let tab_edit = $("<div class='tab_edit'>Add text here...</div>");
    tab_edit.attr("contenteditable", "true");

    let modifyButton = $("<i class='fas fa-ellipsis-v tabmenu' title='Options'></i>");
    modifyButton.attr("contenteditable", "false");

    let menuState = false; //False = closed, True = opened

    modifyButton.click(function (ev) {

        if (!menuState) {
            let menu = buildMenu(c1);
            c1.prepend(menu);
            menuState = true;
        } else {
            $(ev.target.parentNode).find(".tab_menu").remove();
            menuState = false;
        }
    });

    c1.resizable({
        handles: 'e, w',
        resize: function (e, ui) {
            let w = c1.width();
            let parent = e.target.parentNode;

            let nbColumns = $(".column", $(parent)).length;

            $(".column", $(parent)).each(function () {
                $(".column").css({left: 0})
                if(!$(this).is(c1))
                {
                    $(this).width(($(parent).width() - w)/(nbColumns-1));
                }
            })
        }
    });

    c1.append(modifyButton);
    c1.append(tab_edit);

    editable.append(c1);
}

/*Bouton permettant d'ajouter un onglet*/
function appendNewTabButton()
{
    let appendTab = $("<div class='addBlock'></div>");
    let appendButton = $('<i class="far fa-2x fa-plus-square" id="undo" aria-hidden="true" title="Nouveau bloc"></i>');
    appendTab.append(appendButton);
    appendButton.click(function (ev) {
        appendTab.remove();
        appendNewTab();
        appendNewTabButton();
    });
    content.append(appendTab);
}

/*Chaine d'executions des actions du menu*/
function menu(ev)
{
    ev.preventDefault(true);
    switch($(ev.target).attr("id"))
    {
        case "undo":
            document.execCommand("undo");
            break;
        case "redo":
            document.execCommand("redo");
            break;
        case "text-type":
            document.execCommand("formatBlock", true, $("#text-type").val());
            break;
        case "bold":
            document.execCommand("bold", true);
            break;
        case "italic":
            document.execCommand("italic", true);
            break;
        case "underline":
            document.execCommand("underline", true);
            break;
        case "color":
            popup("color");
            break;
        case "deformat":
            document.execCommand("removeFormat");
            break;
        case "link":
            popup("link");
            break;
        case "indent":
            document.execCommand("indent", true);
            break;
        case "outdent":
            document.execCommand("outdent", true);
            break;
        case "align-left":
            document.execCommand("justifyLeft", true);
            break;
        case "align-center":
            document.execCommand("justifyCenter", true);
            break;
        case "align-right":
            document.execCommand("justifyRight", true);
            break;
        case "align-justify":
            document.execCommand("justifyFull", true);
            break;
    }
}

/*Popup du menu*/
function popup(type)
{
    let popup = $("#popup");
    popup.html("");
    switch(type)
    {
        case "color":
            popup.append($("<b>Couleur de police</b><br/>"));
            let selector = $("<input type='color' value='#000000'>");
            popup.append(selector);

            selector.change(function () {
                document.execCommand("foreColor", true, selector.val()); //Couleur
            });
            selector.onmouseup = function () {
                document.execCommand("foreColor", true, selector.val()); //Couleur
            };
            break;

        case "link":
            popup.append($("<b>Lien</b><br/>"));
            let link = $("<input type='url' value='' style='width: 100%;'>");
            let confirm = $("<button>Ajouter lien</button>");
            popup.append(link);
            popup.append(confirm);


            let focus = false;
            let selection;
            link.focus(function (ev) {
                if(!focus)
                {
                    focus = true;
                    selection = saveSelection();
                }
            });

            confirm.click(function () {
                restoreSelection(selection);
                if(!document.execCommand("createLink", true, link.val())) //Link
                {
                    alert("Un texte doit être sélectionné pour insérer un lien")
                }
            });
            break;
    }

    $("#content-edit").click(function (ev) {
        if(!popup.is(ev.target) && popup.has(ev.target).length === 0)
        {
            $('#popup').html("");
        }
    })
}

/*Retourne la selection actuelle*/
function saveSelection()
{
    if (window.getSelection)
    {
        let sel = window.getSelection();
        if (sel.getRangeAt && sel.rangeCount)
        {
            return sel.getRangeAt(0);
        }
    }
    else if (document.selection && document.selection.createRange)
    {
        return document.selection.createRange();
    }
    return null;
}

/*Selectionne le texte encadré par $range*/
function restoreSelection(range)
{
    if (range)
    {
        if (window.getSelection)
        {
            var sel = window.getSelection();
            sel.removeAllRanges();
            sel.addRange(range);
        }
        else if (document.selection && range.select)
        {
            range.select();
        }
    }
}

/**/
function resizeAll()
{
    $(".imgResizable").each(function (){
        $(this).children().each(function(){
            if($(this).prop("tagName") !== "IMG")
            {
                $(this).unbind();
                $(this).remove();
            }
        })

        let rmBtn = $("<i class='fas fa-trash-alt' title='Supprimer'></i>")
        let centerBtn = $("<i class='fas fa-align-center' title='Centrer'></i>")
        let leftBtn = $("<i class='fas fa-align-left' title='Gauche'></i>")
        let rightBtn = $("<i class='fas fa-align-right' title='Droite'></i>")

        rmBtn.click(function (ev) {
            $(ev.target.parentNode).remove();
        });

        centerBtn.click(function (ev) {
            $(ev.target.parentNode).css({
                "margin-left": "auto",
                "margin-right": "auto"
            });
        });

        leftBtn.click(function (ev) {
            $(ev.target.parentNode).css({
                "margin-left": "0",
                "margin-right": "auto"
            });
        });

        rightBtn.click(function (ev) {
            $(ev.target.parentNode).css({
                "margin-left": "auto",
                "margin-right": "0"
            });
        });

        $(this).append(rmBtn);
        $(this).append(centerBtn);
        $(this).append(leftBtn);
        $(this).append(rightBtn);

        $(this).resizable({
            minWidth: 100
        });
    });

    $(".mapResizable").each(function (){
        $(this).children().each(function(){
            if($(this).prop("tagName") !== "IFRAME")
            {
                $(this).unbind();
                $(this).remove();
            }
        })

        let rmBtn = $("<i class='fas fa-trash-alt' title='Supprimer'></i>")
        let centerBtn = $("<i class='fas fa-align-center' title='Centrer'></i>")
        let leftBtn = $("<i class='fas fa-align-left' title='Gauche'></i>")
        let rightBtn = $("<i class='fas fa-align-right' title='Droite'></i>")

        rmBtn.click(function (ev) {
            $(ev.target.parentNode).remove();
        });

        centerBtn.click(function (ev) {
            $(ev.target.parentNode).css({
                "margin-left": "auto",
                "margin-right": "auto"
            });
        });

        leftBtn.click(function (ev) {
            $(ev.target.parentNode).css({
                "margin-left": "0",
                "margin-right": "auto"
            });
        });

        rightBtn.click(function (ev) {
            $(ev.target.parentNode).css({
                "margin-left": "auto",
                "margin-right": "0"
            });
        });

        $(this).append(rmBtn);
        $(this).append(centerBtn);
        $(this).append(leftBtn);
        $(this).append(rightBtn);

        $(this).resizable({
            minWidth: 100
        });
    });
}

function formatAll()
{
    $(".imgResizable").each(function () {
        $(this).children().each(function(){
            if($(this).prop("tagName") !== "IMG")
            {
                $(this).unbind();
                $(this).remove();
            }
        })

        let w = $(this).width();
        let pW = $(this.parentNode).width();
        let ratio = (w/pW)*100;

        let vh = $("#content-edit").width();
        let h = $(this).height();
        let hRatio = (h/vh)*100;

        let rate = w/h;

        $(this).css({"width": ratio+"%"});
        $(this).attr("ratio", rate)
    });

    $(".mapResizable").each(function () {
        $(this).children().each(function(){
            if($(this).prop("tagName") !== "IFRAME")
            {
                $(this).unbind();
                $(this).remove();
            }
        })

        let w = $(this).width();
        let pW = $(this.parentNode).width();
        let ratio = (w/pW)*100;

        let vh = $(window).height();
        let h = $(this).height();
        let hRatio = (h/vh)*100;

        let rate = w/h;

        $(this).css({"width": ratio+"%"});
        $(this).attr("ratio", rate)
    });

    $(".diapoResizable").each(function () {
        $(this).children().each(function () {
            $(this).unbind();
            $(this).remove();
        })
    })

    $(".column").each(function () {
        let w = $(this).outerWidth();
        let pW = $($(this).parent()).width();
        let ratio = (w / pW) * 100;

        $(this).find(".ui-resizable-handle").remove();
        $(this).find(".tabmenu").remove();
        $(this).find(".tab_menu").remove();

        $(this).css({"width": ratio + "%"});
    })

    $(".newColumn").remove();

    $(".addBlock").remove();

    $(".tab").find(".tabmenu").remove();
    $(".tab").find(".tab_menu").remove();
}