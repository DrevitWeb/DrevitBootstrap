<?php


namespace basics\form;


class Input
{
    private $type;
    private $name;
    private $displayName;

    private $alpha = false;
    private $email = false;
    private $required = false;

    private $unique = false;
    private $table = null;
    private $field = null;

    private $equals = false;
    private $input = null;

    private $value;

    public function __construct($type, $name, $displayName)
    {
        $this->type = $type;
        $this->name = $name;
        $this->displayName = $displayName;
        $this->value = null;
    }

    public function isAlpha(){return $this->alpha;}
    public function setAlpha($alpha = true)
    {
        $this->alpha = $alpha;
        return $this;
    }

    public function isEmail(){return $this->email;}
    public function setEmail($email = true)
    {
        $this->email = $email;
        return $this;
    }

    public function isRequired(){return $this->required;}
    public function setRequired($required = true)
    {
        $this->required = $required;
        return $this;
    }

    public function isUnique(){return $this->unique;}
    public function getTable(){return $this->table;}
    public function getField(){return $this->field;}
    public function setUnique($table, $field, $unique = true)
    {
        $this->unique = $unique;
        $this->table = $table;
        $this->field = $field;
        return $this;
    }

    public function isEquals(){return $this->equals;}
    public function getInput(){return $this->input;}
    public function setEquals($input, $equals = true)
    {
        $this->input = $input;
        $this->equals = $equals;
        return $this;
    }

    public function getType(){return $this->type;}
    public function setType($type){$this->type = $type;}

    public function getName() {return $this->name;}
    public function setName($name){$this->name = $name;}

    public function getDisplayName() {return $this->displayName;}
    public function setDisplayName($displayName){$this->displayName = $displayName;}

    public function getValue(){return $this->value;}
    public function setValue($value){$this->value = $value;}

    public function clear()
    {
        $this->value = \basics\Utils::convertToBBCODE($this->value);
        $this->value = trim($this->value);
        /*$this->value = str_replace("'", "&apos;", $this->value);
        $this->value = str_replace("\"", "&quot;", $this->value);*/
        //$this->value = stripslashes($this->value);
        //$this->value = htmlspecialchars($this->value);
        $this->value = htmlentities($this->value);
        return $this;
    }
}

