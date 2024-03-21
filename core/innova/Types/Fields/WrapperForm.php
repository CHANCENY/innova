<?php

namespace Innova\Types\Fields;

class WrapperForm
{
    private string $fields;
    private string $wrapperForm;
    private ActionButtons|null $actions;

    public function __construct(private string $method, private string $action, private array $enctype, private string $form_id) {
        $this->fields = '';
        $this->actions = null;
    }

    public function setField(string $fields): WrapperForm {
        $this->fields = $fields;
        return $this;
    }

    public function setAction(ActionButtons $actionButtons):WrapperForm
    {
        $this->actions = $actionButtons;
        return $this;
    }

    public function buildForm(): WrapperForm {
        $enctype = null;
        if(!empty($this->enctype)) {
            foreach ($this->enctype as $key=>$value) {
                $enctype = $key . "= '$value'";
            }
        }

        $form[] = "method='$this->method' action='$this->action' class='form form-wrapper' id='$this->form_id' $enctype";
        $attributes = implode(' ', $form);
        $action = $this->actions->__toString();

        $this->wrapperForm = "<form $attributes>". $this->fields . $action . "</form>";

        return $this;
    }

    public function __toString(): string
    {
        return $this->wrapperForm;
    }

    public static function wrapperForm(string $method, string $action, array $enctype, string $form_id, string $fields, ActionButtons $actionButtons): WrapperForm {
        return (new WrapperForm($method,$action,$enctype,$form_id))->setField($fields)->setAction($actionButtons)->buildForm();
    }
}