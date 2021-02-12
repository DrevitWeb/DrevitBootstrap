<?php


namespace basics\form;

use basics\Database;
use basics\Session;
use basics\Utils;

class Form
{
    private $method;
    private $action;
    private $submitText;
    private $id;
    private $size;

    private $token;

    private $inputs = array();
    private $errors = array();

    public function __construct($method, $action, $submitText, $id="", $size="col-8")
    {
        $this->method = $method;
        $this->action = $action;
        $this->submitText = $submitText;
        $this->id = $id;
        $this->size = $size;

        $this->token = Utils::generateRandomString(8);
    }

    public function addInput($input)
    {
        array_push($this->inputs, $input);
        return $input;
    }

    public function getInput($name)
    {
        foreach($this->inputs as $input)
        {
            if($input->getName() == $name)
            {
                return $input;
            }
        }
        return null;
    }

    public function setInput($name, $value)
    {
        if($this->getInput($name) != null)
        {
            $this->getInput($name)->setValue($value);
        }
    }

    public function getValue($name)
    {
        foreach($this->inputs as $input)
        {
            if($input->getName() == $name)
            {
                return $input->getValue();
            }
        }
        return null;
    }

    public function build()
    {
        $form = "<form autocomplete='none' class='classicForm $this->size' id='$this->id' method='$this->method' action='$this->action' enctype='multipart/form-data'>";
        foreach ($this->inputs as $input)
        {
            if($input->getType() == "textarea")
            {
                $form .= (($input->getDisplayName() != "")?"<label for='{$input->getName()}'>{$input->getDisplayName()}</label>":"")."<textarea name='{$input->getName()}' id='{$input->getName()}'></textarea>";
            }

            else if($input->getType() == "BBtextarea")
            {
                $form .=
                    '<label for="'.$input->getName().'\">'.$input->getDisplayName().'</label>
                    <div class="BBtextarea"><div contenteditable="true" class="'.$input->getName().$this->token.'_box bbcodeDiv col20 mcol20" name="'.$input->getName().'" id="id_'.$input->getName().$this->token.'"></div></div>
                    <textarea name="'.$input->getName().'" class="'.$input->getName().$this->token.'_txt" style="display: none;"></textarea>
                    <script>$("#'.$this->id.'").submit(function (){$(".'.$input->getName().$this->token.'_txt").html($(".'.$input->getName().$this->token.'_box").html());});</script>
                    <script>$(\'.bbcodeDiv#id_'.$input->getName().$this->token.'\').markItUp(myBbcodeSettings);</script>
                    ';
            }
            else if($input->getType() == "hidden")
            {
                $form .= "<input autocomplete='none' value='{$input->getValue()}' type='{$input->getType()}' name='{$input->getName()}' id='{$input->getName()}'/>";
            }
            else if($input->getType() == "checkbox")
            {
                $form .= "<div class='form-group'><label for='{$input->getName()}'>{$input->getDisplayName()}</label><input autocomplete='none' value='{$input->getValue()}' type='{$input->getType()}' name='{$input->getName()}' id='{$input->getName()}'/></div>";
            }
            else if($input->getType() == "file")
            {
                $form .= "<div class='form-group'><label for='{$input->getName()}'>{$input->getDisplayName()}</label><input autocomplete='none' type='{$input->getType()}' name='{$input->getName()}' id='{$input->getName()}' ".(($input->isMultiple())?"multiple":"")."/></div>";
            }
            else if(!($input instanceof Select))
            {
                $form .= "<div class='form-group'><label for='{$input->getName()}'>{$input->getDisplayName()}</label><input autocomplete='none' type='{$input->getType()}' name='{$input->getName()}' id='{$input->getName()}'/></div>";
            }
            else
            {
                $form .= "<div class='form-group'><label for='{$input->getName()}'>{$input->getDisplayName()}</label>";
                $form .= "<select name='{$input->getName()}' id='{$input->getName()}'>";
                $form .= "<option disabled selected>---</option>";
                foreach ($input->getOptions() as $option => $value)
                {
                    $form .= "<option ".(($input->getSelected() == $option)?"selected":"")."value='$option'>$value</option>";
                }
                $form .= "</select></div>";
            }
            $form .= "<br/>";
        }
        $form .= "<br/>";
        $form .= "<input type='hidden' name='token' value='$this->token'/>";
        $form .= "<input type='submit' value='".$this->submitText."'/>";
        $form .= "</form>";

        /*Enregistrement du formulaire pour validation*/
        if(Session::get("forms") == null)
        {
            Session::set("forms", array());
        }
        $_SESSION['forms'][$this->token] = serialize($this);

        return $form;
    }

    public function getFormCorrected()
    {
        $form = "<form autocomplete='none' class='classicForm $this->size' id='$this->id' method='$this->method' action='$this->action' enctype='multipart/form-data'>";
        foreach ($this->inputs as $input)
        {
            if(isset($this->errors[$input->getName()]) && !empty(($this->errors[$input->getName()])))
            {
                $form .=  "<div class='form-error'>".$this->errors[$input->getName()]."</div><br/>";
            }
            if($input->getType() == "textarea")
            {
                $form .= "<label for='{$input->getName()}'>{$input->getDisplayName()}</label><textarea value='' name='{$input->getName()}' id='{$input->getName()}'>".(($input->getType() != "password")?$input->getValue():"")."</textarea>";
            }
            else if($input->getType() == "BBtextarea")
            {
                $form .=
                    '<label for="'.$input->getName().'\">'.$input->getDisplayName().'</label>
                    <div class="BBtextarea"><div contenteditable="true" class="'.$input->getName().$this->token.'_box bbcodeDiv col20 mcol20" name="'.$input->getName().'" id="id_'.$input->getName().$this->token.'">'.(($input->getType() != "password" )?str_replace("\n", "<br>", $input->getValue()):"").'</div></div>
                    <textarea name="'.$input->getName().'" class="'.$input->getName().$this->token.'_txt" style="display: none;"></textarea>
                    <script>$("#'.$this->id.'").submit(function (){$(".'.$input->getName().$this->token.'_txt").html($(".'.$input->getName().$this->token.'_box").html());});</script>
                    <script>$(\'.bbcodeDiv#id_'.$input->getName().$this->token.'\').markItUp(myBbcodeSettings);</script>
                    ';
            }
            else if($input->getType() == "hidden")
            {
                $form .= "<input autocomplete='none' value='".(($input->getType() != "password")?$input->getValue():"")."' type='{$input->getType()}' name='{$input->getName()}' id='{$input->getName()}'/>";
            }
            else if(!($input instanceof Select))
            {
                $form .= "<div class='form-group'><label for='{$input->getName()}'>{$input->getDisplayName()}</label><input autocomplete='none' value='".(($input->getType() != "password")?str_replace("'", "&apos;",$input->getValue()):"")."' type='{$input->getType()}' name='{$input->getName()}' id='{$input->getName()}'/></div>";
            }
            else
            {
                $form .= "<div class='form-group'><label for='{$input->getName()}'>{$input->getDisplayName()}</label>";
                $form .= "<select name='{$input->getName()}' id='{$input->getName()}'>";
                $form .= "<option disabled selected>---</option>";
                foreach ($input->getOptions() as $option => $value)
                {
                    $form .= "<option ".(($input->getValue() == $option)?"selected ":"")."value='$option'>$value</option>";
                }
                $form .= "</select></div>";
            }
            $form .= "";
        }
        $form .= "<br/>";
        $form .= "<input type='hidden' name='token' value='$this->token'/>";
        $form .= "<input type='submit' value=\"".$this->submitText."\"/>";
        $form .= "</form>";

        return $form;
    }

    public function readForm()
    {
        foreach($_POST as $name => $value)
        {
            if($this->getInput($name) != null)
            {
                $this->getInput($name)->setValue($value);
                if($this->getInput($name)->getType() != "file") $this->getInput($name)->clear();
            }
        }
        $_SESSION['forms'][$this->token] = serialize($this);
    }

    public function checkForm()
    {
        $this->readForm();
        foreach ($this->inputs as $input)
        {
            if($input->getValue() != null)
            {
                if ($input->isAlpha() && !preg_match('/^[a-zA-Z0-9,_\s-]+$/', $input->getValue()))
                {
                   $this->errors[$input->getName()] = $input->getDisplayName() . " doit être Alphanumérique.";
                }
                if ($input->isEmail() && !filter_var($input->getValue(), FILTER_VALIDATE_EMAIL))
                {
                    $this->errors[$input->getName()] = $input->getDisplayName() . " doit être un email.";
                }
                if($input->isUnique() && Database::query("SELECT * FROM ".$input->getTable()." WHERE ".$input->getField()."='".$input->getValue()."'")->fetch() != false)
                {
                    $this->errors[$input->getName()] = "\"".$input->getValue()."\" est déjà utilisé";
                }
                if($input->isEquals() && $this->getInput($input->getInput())->getValue() != $input->getValue())
                {
                    $this->errors[$input->getName()] = $this->getInput($input->getInput())->getDisplayName()." et ".$input->getDisplayName()." doivent être identiques.";
                }
            }
            else if($input->isRequired())
            {
                if($input->getType() != "file")
                {
                    $this->errors[$input->getName()] = $input->getDisplayName() . " doit être renseigné.";
                }
                else
                {
                    if(!isset($_FILES[$input->getName()]) || $_FILES[$input->getName()]['error'] == UPLOAD_ERR_NO_FILE) {
                        $this->errors[$input->getName()] = $input->getDisplayName() . " doit être renseigné.";
                    }
                }
            }
        }

        if(sizeof($this->errors) == 0)
        {
            return true;
        }
        return false;
    }

    public function deleteForm()
    {
        unset($_SESSION['forms'][$this->token]);
    }

    public function getErrors(){return $this->errors;}

    public function getToken(){return $this->token;}
}