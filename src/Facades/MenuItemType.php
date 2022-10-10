<?php

namespace AmphiBee\MenuItemTypeMaker\Facades;

use AmphiBee\MenuItemTypeMaker\Support\MenuItem;

class MenuItemType
{
    protected $label;
    protected $slug;
    protected $view;
    protected $icon;
    protected $fields = [];
    protected $fieldGroup;
    protected $itemAttributes = [];

    public function __construct($label, $slug)
    {
        $this->label = $label;
        $this->slug = $slug;
    }

    public function __destruct()
    {
        new MenuItem($this->label, $this->slug, $this->icon, $this->view, $this->itemAttributes, $this->fields, $this->fieldGroup);
    }

    /**
     * Instantiate a new menu item type
     * @param string $label The display title for your menu item type
     * @param string|null $slug A unique slug that identifies the menu item type
     * @return static
     */
    public static function make(string $label, string $slug): self
    {
        return new static($label, $slug);
    }

    /**
     * Set the icon for the menu item type (path or inline svg)
     *
     * @param string $icon
     * @return $this
     */
    public function setIcon(string $icon): self
    {
        $this->icon = $icon;
        return $this;
    }

    /**
     * Set the fields to display in the menu item editor (with Extended ACF)
     *
     * @param array $fields
     * @return $this
     */
    public function setFields(array $fields): self
    {
        $this->fields = $fields;
        return $this;
    }

    /**
     * Set the native acf field group for the menu item type
     *
     * @param string $fieldGroup
     * @return $this
     */
    public function setFieldGroup(string $fieldGroup): self
    {
        $this->fieldGroup = $fieldGroup;
        return $this;
    }

    /**
     * Set the view for the menu item
     *
     * @param string $view
     * @return $this
     */
    public function setView(string $view): self
    {
        $this->view = $view;
        return $this;
    }

    /**
     * Set specific menu item attributes
     *
     * @param array $itemAttributes
     * @return $this
     */
    public function setItemAttributes(array $itemAttributes): self
    {
        $this->itemAttributes = $itemAttributes;
        return $this;
    }
}