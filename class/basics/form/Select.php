<?php


namespace basics\form;


class Select extends Input
{
    private $options = array();
    private $selected = null;

    public function __construct($name, $displayName)
    {
        Input::__construct("select", $name, $displayName);
        return $this;
    }


    public function getOptions(){return $this->options;}
    public function addOption($name, $value) : Select
    {
        $this->options[$name] = $value;
        return $this;
    }

    public function getSelected(){return $this->selected;}
    public function setSelected($selected)
    {
        $this->selected = $selected;
    }
}