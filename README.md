# Menu Item Type Maker

> Register menu item type with object oriented PHP.

Menu Item Type Maker provides an object oriented API to register menu item type with the [Menu Item Types plugin](https://fr.wordpress.org/plugins/menu-item-types).

- [Installation](#installation)
- [Register a menu item type](#register-a-menu-item-type)

## Installation

Require this package, with Composer, in the root directory of your project.

```bash
composer require amphibee/menu-item-type-maker
```

Download the [Menu Item Types plugin](https://fr.wordpress.org/plugins/menu-item-types) and put it in either the `plugins` or `mu-plugins` directory. Visit the WordPress dashboard and activate the plugin.

## Register a menu item type

### The method way

Use the `MenuItemType::make()` function to register a new menu item type. Below you'll find an example of a menu item registration.
Currently, this wrapper supports classic PHP, twig and blade views.

**With the default ACF plugin and WordPress classic template**

```php
use AmphiBee\MenuItemTypeMaker\Facades\MenuItemType;

MenuItemType::make('Card', 'card')
            ->setIcon('path/to/my/icon.svg')
            ->setView('parts/nav/item-types/card.php')
            ->setFieldGroup('path/to/acf/field-group.php');
```

**With [Extended ACF](https://github.com/vinkla/extended-acf)** and a Blade view

```php
use AmphiBee\MenuItemTypeMaker\Facades\MenuItemType;
use Extended\ACF\Fields\Image;
use Extended\ACF\Fields\Text;

MenuItemType::make('Card', 'card')
            ->setIcon('<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
    </svg>')
            ->setView('parts/nav/item-types/card')
            ->setFields([
                Image::make('Image'),
                Text::make('Title'),
            ]);
```

### The extended way

```php
use AmphiBee\MenuItemTypeMaker\Contracts\MenuItemAbstract;
use Extended\ACF\Fields\Image;
use Extended\ACF\Fields\Text;

class CardMenuProvider extends MenuItemAbstract
{
    protected $label = 'Card';
    protected $slug = 'card';
    protected $view = 'parts/nav/item-types/card.twig';
    protected $icon = '<svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 15.75l5.159-5.159a2.25 2.25 0 013.182 0l5.159 5.159m-1.5-1.5l1.409-1.409a2.25 2.25 0 013.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 001.5-1.5V6a1.5 1.5 0 00-1.5-1.5H3.75A1.5 1.5 0 002.25 6v12a1.5 1.5 0 001.5 1.5zm10.5-11.25h.008v.008h-.008V8.25zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
    </svg>';

    protected function getFields(): array
    {
        return [
            Image::make('Image'),
            Text::make('Title'),
        ];
    }
}
```
