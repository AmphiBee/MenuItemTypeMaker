<?php

namespace AmphiBee\MenuItemTypeMaker\Support;

use AmphiBee\MenuItemTypeMaker\Contracts\MenuItemAbstract;

class MenuItem extends MenuItemAbstract
{
    protected $label;
    protected $slug;
    protected $view;
    protected $icon;
    protected $itemAttributes;

    public function __construct($label, $slug, $icon, $view, $itemAttributes, $fields)
    {
        $this->label = $label;
        $this->slug = $slug;
        $this->icon = $icon;
        $this->view = $view;
        $this->itemAttributes = $itemAttributes;
        $this->fields = $fields;

        parent::__construct();
    }

}
