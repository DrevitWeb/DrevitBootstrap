<div class="alert alert-error" id="formError"></div>
    <label>Titre</label>&nbsp;<input type="text" id="title">
    <label>Identifiant</label>&nbsp;<input type="text" id="page_id">
    <ul id="editor-actions">
        <li>
            <select id="text-type">
                <option value="p">Paragraphe</option>
                <option value="h1">Titre 1</option>
                <option value="h2">Titre 2</option>
                <option value="h3">Titre 3</option>
                <option value="h4">Titre 4</option>
                <option value="h5">Titre 5</option>
            </select>
        </li>
        <li>
            <input list="fontSize" name="fontSize" id="font-size"  value='16' style="width: 90%;">
            <datalist id="fontSize">
                <option value="5">
                <option value="10">
                <option value="12">
                <option value="14">
                <option value="16">
                <option value="18">
                <option value="20">
                <option value="22">
                <option value="24">
                <option value="26">
                <option value="28">
                <option value="30">
                <option value="32">
            </datalist>
        </li>
        <li><a href="javascript:" title="Annuler" class="fas fa-undo" id="undo"></a></li>
        <li><a href="javascript:" title="Restaurer" class="fas fa-redo" id="redo"></a></li>
        <li><a href="javascript:" title="Gras" class="fas fa-bold" id="bold"></a></li>
        <li><a href="javascript:" title="Italique" class="fas fa-italic" id="italic"></a></li>
        <li><a href="javascript:" title="Souligné" class="fas fa-underline" id="underline"></a></li>
        <li><a href="javascript:" title="Couleur" class="fas fa-tint" id="color"></a></li>
        <li><a href="javascript:" title="Enlever le style" class="fas fa-remove-format" id="deformat"></a></li>
        <li><a href="javascript:" title="Lien" class="fas fa-link" id="link"></a></li>
        <li><a href="javascript:" title="Décalage à droite" class="fas fa-indent" id="indent"></a></li>
        <li><a href="javascript:" title="Décalage à gauche" class="fas fa-outdent" id="outdent"></a></li>
        <li><a href="javascript:" title="Gauche" class="fas fa-align-left" id="align-left"></a></li>
        <li><a href="javascript:" title="Centré" class="fas fa-align-center" id="align-center"></a></li>
        <li><a href="javascript:" title="Droite" class="fas fa-align-right" id="align-right"></a></li>
        <li><a href="javascript:" title="Justifié" class="fas fa-align-justify" id="align-justify"></a></li>
    </ul>
    <div id="popup"></div>
    <div id="content-edit" contenteditable="false">

</div>

<div id="sendPageBlock">
    <?php
    $form = new \basics\form\Form("post", '', "Publier la page", "sendPage");
    echo $form->build();
    ?>
</div>
<div id="modifyPageBlock" style="display: none;">
    <?php
    $form = new \basics\form\Form("post", '', "Modifier la page", "modifyPage");
    echo $form->build();
    ?>
</div>
<script>
    /*Mise en place du menu des onglets*/
    function buildMenu(attachment)
    {
        let menu = $("<div class='tab_menu'></div>");
        let image = $("<i class='far fa-image fa-2x tab_btn' title='Image'></i>");
        let columns = $("<i class='fas fa-columns fa-2x tab_btn' title='Colonnes'></i>");
        let map = $("<i class='fas fa-map-marked-alt fa-2x tab_btn' title='Carte'></i>");
        let diapo = $("<i class='far fa-images fa-2x tab_btn' title='Diapo'></i>");
        let rm = $("<i class='fas fa-trash-alt fa-2x tab_btn' title='Supprimer'></i>");

        image.click(function (ev) {
            <?php
            $form = new \basics\form\Form("POST", "", "Envoyer", "addPicture", "col-20");
            $form->addInput(new \basics\form\Input("file", "img", "Choisir une image"))->setRequired();
            echo "let form=\"".$form->build()."\";";
            ?>

            registerForm("addPicture", "addPicture", function (data) {
                data = "<div class='imgResizable' contenteditable='false'><img style='' src='"+data+"' /></div>";
                let img = $(data);
                let rmBtn = $("<i class='fas fa-trash-alt' title='Supprimer'></i>")
                let centerBtn = $("<i class='fas fa-align-center' title='Centrer'></i>")
                let leftBtn = $("<i class='fas fa-align-left' title='Gauche'></i>")
                let rightBtn = $("<i class='fas fa-align-right' title='Droite'></i>")

                img.append(rmBtn);
                img.append(centerBtn);
                img.append(leftBtn);
                img.append(rightBtn);

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

                img.resizable({
                    minWidth: 100
                });
                attachment.children(".tab_edit").append(img);
                attachment.children(".tab_edit").append($("<div></div>"));
                attachment.find("#addPicture").remove();
            });

            $(ev.target.parentNode).remove();
            attachment.prepend(form);
        });

        columns.click(function (ev) {
            let editable = attachment.find(".tab_edit");
            editable.attr("contenteditable", "false");
            let oldText = editable.html();
            editable.text("");

            editable.css("display", "flex");

            addColumn(editable);
            addColumn(editable);

            let newColumn = $("<i class='fas fa-plus-square newColumn' title='Nouvelle Colonne'></i>");

            $(editable).parent().prepend(newColumn);

            newColumn.click(function () {
                addColumn(editable);
            });

            $($(editable.children(".column")[0]).children(".tab_edit")).html(oldText)
            $(ev.target.parentNode).remove();

            resizeAll();
        });

        map.click(function (ev) {
            <?php
            $form = new \basics\form\Form("POST", "", "Envoyer", "createMap", "col-20");
            $form->addInput(new \basics\form\Input("text", "address", "Adresse"))->setRequired();
            echo "let form=\"".$form->build()."\";";
            ?>

            registerForm("createMap", "createMap", function (data) {
                let map = $("<div class='mapResizable'>"+data+"</div>");
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

                map.resizable({
                    minWidth: 100
                });

                rmBtn.click(function (ev) {
                    $(ev.target.parentNode).remove();
                });

                map.append(rmBtn);
                map.append(centerBtn);
                map.append(leftBtn);
                map.append(rightBtn);

                attachment.children(".tab_edit").append(map);
                attachment.children(".tab_edit").append($("<div></div>"));
                attachment.find("#createMap").remove();
            });

            $(ev.target.parentNode).remove();
            attachment.prepend(form);
        });

        diapo.click(function (ev) {
            ajaxRequest("post", "/class/controllers/pagesController.php?func=getDiapo", function (data) {
                let diapo = JSON.parse(data).diapo;
                <?php
                $form = new \basics\form\Form("POST", "", "Envoyer", "addPicture", "col-20");
                $form->addInput(new \basics\form\Input("file", "img", "Choisir une image"))->setRequired();
                echo "let form=\"".$form->build()."\";";
                ?>

                data = "<div class='diapoResizable' contenteditable='false'>"+diapo+"</div>";
                diapo = $(data);
                let rmBtn = $("<i class='fas fa-trash-alt' title='Supprimer' style='z-index: 100'></i>")
                let centerBtn = $("<i class='fas fa-align-center' title='Centrer' style='z-index: 100'></i>")
                let leftBtn = $("<i class='fas fa-align-left' title='Gauche' style='z-index: 100'></i>")
                let rightBtn = $("<i class='fas fa-align-right' title='Droite' style='z-index: 100'></i>")

                diapo.append(rmBtn);
                diapo.append(centerBtn);
                diapo.append(leftBtn);
                diapo.append(rightBtn);

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

                diapo.resizable({
                    minWidth: 100
                });
                attachment.children(".tab_edit").append(diapo);
                attachment.children(".tab_edit").append($("<div></div>"));
            });

            $(ev.target.parentNode).remove();
        })

        rm.click(function () {
                attachment.remove();
            }
        );

        menu.append(image);
        menu.append(columns);
        menu.append(map);
        menu.append(diapo);
        menu.append(rm);
        return menu;
    }

    function reformatAll()
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

            let w = $(this).width();
            let rate = $(this).attr("ratio");
            let h = w/rate;
            $(this).css("height", h+"px");
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


            let w = $(this).width();
            let rate = $(this).attr("ratio");
            let h = w/rate;
            console.log(h);
            $(this).css("height", h+"px");
        });

        $('.column').each(function () {
            let modifyButton = $("<i class='fas fa-ellipsis-v tabmenu' title='Options'></i>");
            modifyButton.attr("contenteditable", "false");
            let menuState = false; //False = closed, True = opened

            let col = $(this);

            $(this).resizable({
                handles: 'e, w',
                resize: function (e, ui) {
                    let w = col.width();
                    let parent = e.target.parentNode;

                    let nbColumns = $(".column", $(parent)).length;

                    $(".column", $(parent)).each(function () {
                        $(".column").css({left: 0})
                        if(!$(this).is($(col)))
                        {
                            $(this).width(($(parent).width() - w)/(nbColumns-1));
                        }
                    })
                }
            });

            modifyButton.click(function (ev) {

                if(!menuState)
                {
                    let menu = buildMenu(col);
                    col.prepend(menu);
                    menuState = true;
                }
                else
                {
                    $(ev.target.parentNode).find(".tab_menu").remove();
                    menuState = false;
                }
            });
            $(this).append(modifyButton);
        })

        $(".tab").each(function () {
            if($(this).find(".column").length !== 0)
            {
                let newColumn = $("<i class='fas fa-plus-square newColumn' title='Nouvelle Colonne'></i>");

                $(this).prepend(newColumn);

                let tab = $(this);

                newColumn.click(function () {
                    addColumn(tab.find(".tab_edit"));
                });

            }
            else
            {
                let modifyButton = $("<i class='fas fa-ellipsis-v tabmenu' title='Options'></i>");
                modifyButton.attr("contenteditable", "false");
                let menuState = false; //False = closed, True = opened

                let tab = $(this);

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
                $(this).append(modifyButton);
            }
        })

        $(".diapoResizable").each(function () {
            $(this).html();
            let diapoContainer = $(this);
            ajaxRequest("post", "/class/controllers/pagesController.php?func=getDiapo", function (data) {
                let diapo = JSON.parse(data).diapo;
                <?php
                $form = new \basics\form\Form("POST", "", "Envoyer", "addPicture", "col-20");
                $form->addInput(new \basics\form\Input("file", "img", "Choisir une image"))->setRequired();
                echo "let form=\"".$form->build()."\";";
                ?>

                diapo = $(diapo);
                let rmBtn = $("<i class='fas fa-trash-alt' title='Supprimer' style='z-index: 100'></i>")
                let centerBtn = $("<i class='fas fa-align-center' title='Centrer' style='z-index: 100'></i>")
                let leftBtn = $("<i class='fas fa-align-left' title='Gauche' style='z-index: 100'></i>")
                let rightBtn = $("<i class='fas fa-align-right' title='Droite' style='z-index: 100'></i>")

                diapoContainer.append(rmBtn);
                diapoContainer.append(centerBtn);
                diapoContainer.append(leftBtn);
                diapoContainer.append(rightBtn);

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

                diapoContainer.resizable({
                    minWidth: 100
                });
                diapoContainer.append(diapo);
            });
        })

        appendNewTabButton();
    }
</script>
<script src="res/scripts/modules/html-editor.js"></script>
<br/>