<?php

namespace AmphiBee\MenuItemTypeMaker\Contracts;

abstract class MenuItemAbstract
{
    /**
     * @var string $label : Menu Item Type label
     */
    protected $label;

    /**
     * @var string $slug : Menu Item Type slug
     */
    protected $slug;

    /**
     * @var string $view : Menu Item Type view file
     */
    protected $view;

    /**
     * @var string $icon : Menu Item Type icon (file path or inline svg)
     */
    protected $icon;

    /**
     * @var $fieldGroup : Native acf field group path.
     */
    protected $fieldGroup;

    /**
     * @var array $itemAttributes : Attributes of the menu item
     */
    protected $itemAttributes = [];

    /**
     * @var array $fields : List of ACF Fields
     */
    protected $fields = [];

    protected $args = [];

    public function __construct()
    {
        add_action('acf/init', [$this, 'registerFields']);
        add_action('plugins_loaded', [$this, 'menuBoot']);
        add_filter('mitypes_item_types', [$this, 'addItemTypes']);
        add_filter('mitypes_nav_menu_link_attributes', [$this, 'itemAttributes'], 11, 5);
    }

    /**
     * Return the label
     *
     * @return string
     */
    protected function getLabel(): string
    {
        return $this->label;
    }

    /**
     * Return the icon
     *
     * @return string
     */
    protected function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * Return the native acf field group path.
     *
     * @return mixed
     */
    protected function getFieldGroup()
    {
        return $this->fieldGroup;
    }


    /**
     * Return the slug
     *
     * @return string
     */
    protected function getSlug(): string
    {
        return $this->slug;
    }

    /**
     * Return the slug
     *
     * @return string
     */
    protected function getView(): string
    {
        return $this->view;
    }

    /**
     * Set the ACF field
     *
     * @return array
     */
    protected function getFields(): array
    {
        return $this->fields;
    }

    /**
     * Set the ACF field
     *
     * @return array
     */
    protected function getItemAttributes(): array
    {
        return $this->itemAttributes;
    }



    /**
     * Register the Menu Items Fields (with extended acf).
     * @return void
     */
    public function registerFields(): void
    {
        if ($this->getFieldGroup()) {
            return;
        }
        register_extended_field_group([
            'title' => "Menu: {$this->getLabel()}",
            'fields' => $this->getFields(),
            'location' => [
                $this->getLocation(),
            ],
        ]);
    }

    /**
     * Return the location of the menu fields (Extended ACF).
     * @return false|Location
     */
    protected function getLocation() {
        if (class_exists('WordPlate\Acf\Location')) {
            return \WordPlate\Acf\Location::if('mitypes', $this->getSlug());
        }
        if (class_exists('WordPlate\Acf\Location')) {
            return method_exists('WordPlate\Acf\Location', 'where') ? \WordPlate\Acf\Location::where('mitypes', $this->getSlug()) : \WordPlate\Acf\Location::if('mitypes', $this->getSlug());
        }
        if (class_exists('Extended\ACF\Location')) {
            return method_exists('Extended\ACF\Location', 'where') ? \Extended\ACF\Location::where('mitypes', $this->getSlug()) : \Extended\ACF\Location::if('mitypes', $this->getSlug());
        }
        return false;
    }

    /**
     * Alert if Menu Item Types plugin is not installed/enabled
     *
     * @return void
     */
    public function menuBoot(): void
    {
        if (!$this->isMiTypesLoaded()) {
            add_action('admin_notices', [$this, 'noticePluginRequired']);
        }
    }

    /**
     * Handle attributes : skip href
     *
     * @param array $atts : Menu item attributes
     * @param $item : Menu item object
     * @param object $args : Menu arguments
     * @param int $depth : Depth of the item
     * @param string $custom_item_type : Type of menu item
     * @return array
     */
    public function itemAttributes(array $atts, $item, object $args, int $depth, string $custom_item_type): array
    {
        return count($this->getItemAttributes()) > 0 ? array_merge($this->getItemAttributes(), $atts) : $atts;
    }

    public function viewArgs(): array
    {
        return [];
    }

    /**
     * Add custom nav menu item
     *
     * @param array $types
     * @return array
     */
    public function addItemTypes(array $types): array
    {
        $types[] = [
            'slug' => $this->getSlug(),
            'icon' => $this->getIcon(),
            'label' => $this->getLabel(),
            'field-group' => $this->getFieldGroup(),
            'render_callback' => function ($item, string $custom_item_type, object $args, int $depth) {
                $viewArgs = array_merge([
                    'item' => $item,
                    'depth' => $depth,
                    'args' => $args,
                    'custom_item_type' => $custom_item_type
                ], $this->viewArgs());
                return $this->render($this->getView(), $viewArgs);
            },
        ];
        return $types;
    }

    /**
     * Render the template
     * @param string $tpl Template file or view path
     * @param array $args View arguments
     * @return void
     */
    public function render(string $tpl = '', array $args = [])
    {
        $tpl = str_replace(['.blade.php'], '', $tpl);

        if (function_exists('view') && view()->exists($tpl)) {
            return view($tpl, $args);
        }

        if (function_exists('\Roots\view') && \Roots\view()->exists($tpl)) {
            return \Roots\view($tpl, $args);
        }

        if (class_exists('\Timber')) {
            $timber = new \Timber\Loader();
            if ($timber->get_loader()->exists($tpl)) {
                return $timber->render($this->view, $args);
            }
        }

        $locatedTemplate = locate_template($tpl, false, false, $args);

        if ($locatedTemplate) {
            ob_start();
            extract($args);
            include($locatedTemplate);
            return ob_get_clean();
        }
    }

    /**
     * Test if Menu Items Types is loaded
     * @return bool
     */
    protected function isMiTypesLoaded(): bool
    {
        /**
         * Load ACF & configure it
         */
        include_once(ABSPATH . 'wp-admin/includes/plugin.php');

        if (!is_plugin_active('menu-item-types/menu-item-types.php')) {
            return false;
        }

        return true;
    }

    /**
     * MITYPES notice
     * @return void
     */
    public function noticePluginRequired(): void
    {

        //print the message
        $mitypes_search_url = 'plugin-install.php?s=menu-item-types&tab=search&type=term';
        $mitypes_link = get_admin_url() . $mitypes_search_url;

        echo '<div id="message" class="error notice is-dismissible">
        <p>' . __('Please install and activate', 'menu-item-types') . ' ' . '<a href="' . $mitypes_link . '">Menu Item Types</a>' . ' ' . __('for using Menu Item Types — Button plugin.', 'mitypes-button') . '</p>
    </div>';

        //make sure to remove notice after its displayed so its only displayed when needed.
        remove_action('admin_notices', [$this, 'noticePluginRequired']);
    }


}
