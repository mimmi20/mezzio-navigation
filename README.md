# mezzio-navigation

[![Latest Stable Version](https://poser.pugx.org/mimmi20/mezzio-navigation/v/stable?format=flat-square)](https://packagist.org/packages/mimmi20/mezzio-navigation)
[![Latest Unstable Version](https://poser.pugx.org/mimmi20/mezzio-navigation/v/unstable?format=flat-square)](https://packagist.org/packages/mimmi20/mezzio-navigation)
[![License](https://poser.pugx.org/mimmi20/mezzio-navigation/license?format=flat-square)](https://packagist.org/packages/mimmi20/mezzio-navigation)

## Code Status

[![codecov](https://codecov.io/gh/mimmi20/mezzio-navigation/branch/master/graph/badge.svg)](https://codecov.io/gh/mimmi20/mezzio-navigation)
[![Average time to resolve an issue](https://isitmaintained.com/badge/resolution/mimmi20/mezzio-navigation.svg)](https://isitmaintained.com/project/mimmi20/mezzio-navigation "Average time to resolve an issue")
[![Percentage of issues still open](https://isitmaintained.com/badge/open/mimmi20/mezzio-navigation.svg)](https://isitmaintained.com/project/mimmi20/mezzio-navigation "Percentage of issues still open")
[![Mutation testing badge](https://img.shields.io/endpoint?style=flat&url=https%3A%2F%2Fbadge-api.stryker-mutator.io%2Fgithub.com%2Fmimmi20%2Fmezzio-navigation%2Fmaster)](https://dashboard.stryker-mutator.io/reports/github.com/mimmi20/mezzio-navigation/master)

## Introduction

This component provides a component for managing trees of pointers to web pages.
Simply put: It can be used for creating menus, breadcrumbs, links, and sitemaps,
or serve as a model for other navigation related purposes.

Unlike in [laminas-navigation](https://github.com/laminas/laminas-navigation) 
this library provides a middleware which is required to prepare the navigation.

## Installation

You can install the mezzio-navigation library with
[Composer](https://getcomposer.org):

```bash
composer require mimmi20/mezzio-navigation
```

## Pages and Containers

There are two main concepts in mezzio-navigation: pages and containers.

### Pages

A page in mezzio-navigation, in its most basic
form, is an object that holds a pointer to a web page. In addition to the
pointer itself, the page object contains a number of other properties that are
typically relevant for navigation, such as `label`, `title`, etc.

Read more about pages in the [pages](#pages) section.

### Containers

A navigation container holds pages. It has
methods for adding, retrieving, deleting and iterating pages. It implements the
[SPL](http://php.net/spl) interfaces `RecursiveIterator` and `Countable`, and
can thus be iterated with SPL iterators such as `RecursiveIteratorIterator`.

Read more about containers in the [containers](#containers) section.

> ### Pages are containers
>
> `Mezzio\Navigation\PageInterface` extends `Mezzio\Navigation\ContainerInterface`,
> which means that a page can have sub pages.

## View Helpers

### Separation of data (model) and rendering (view)

Classes in the mezzio-navigation namespace do not deal with rendering of
navigational elements.  Rendering is done with navigational view helpers.
However, pages contain information that is used by view helpers when rendering,
such as `label`, `class` (CSS), `title`, `lastmod`, and `priority` properties
for sitemaps, etc.

We provide one rendering libary.

- The Renderer using Laminas View is provided by [mezzio-navigation-laminasviewrenderer](https://github.com/mimmi20/mezzio-navigation-laminasviewrenderer/).

The renderer is installable via [Composer](https://getcomposer.org):

```bash
composer require mimmi20/mezzio-navigation-laminasviewrenderer
```

## Containers

Containers have methods for adding, retrieving, deleting, and iterating pages.
Containers implement the [SPL](http://php.net/spl) interfaces
`RecursiveIterator` and `Countable`, meaning that a container can be iterated
using the SPL `RecursiveIteratorIterator` class.

### Creating containers

`Mezzio\Navigation\ContainerInterface` can not be instantiated directly. Use
`Mezzio\Navigation\Navigation` if you want to instantiate a container.

`Mezzio\Navigation\Navigation` can be constructed entirely empty, or take an array
or a `Traversable` object with pages to put in the container. Each page provided
via options will eventually be passed to the `addPage()` method of the container
class, which means that each element in the options can be also be an array,
Traversable object, or a `Mezzio\Navigation\Page\PageInterface` instance.

#### Creating a container using an array

> Unlike in [Laminas Navigation](https://github.com/laminas/laminas-navigation) it is not possible
> to add an arrray config to Mezzio Navigation directly. Converting an array config into a list of
> Page objects is made in the Navigation Factories.

```php
use Mimmi20\Mezzio\Navigation\Navigation;
use Mimmi20\Mezzio\Navigation\Config\NavigationConfigInterface;

/*
 * Create a container from an array
 */
$navigationConfig = $serviceLocator->get(NavigationConfigInterface::class);
$navigationConfig->setPages([
    [
        'label' => 'Page 1',
        'id' => 'home-link',
        'uri' => '/',
    ],
    [
        'label' => 'Laminas',
        'uri' => 'http://www.laminas-project.com/',
        'order' => 100,
    ],
    [
        'label' => 'Page 2',
        'route' => 'page2',
    ],
]);
$container = $serviceLocator->get(Navigation::class);
```

### Adding pages

Adding pages to a container can be done with the methods `addPage()`,
`addPages()`, or `setPages()`.  See examples below for explanation.

> Unlike in [Laminas Navigation](https://github.com/laminas/laminas-navigation) only objects implementing
> the PageInterface are allowed. The PageFactory may be used to use an array config.

```php
use Mimmi20\Mezzio\Navigation\Navigation;
use Mimmi20\Mezzio\Navigation\Page\PageFactory;

// create container
$container = new Navigation();

// add page by giving a page instance
$container->addPage(new Class implements PageInterface{});

// add page by giving a page instance using the PageFactory
$container->addPage((new PageFactory())->factory([
    'uri' => 'http://www.example.com/',
]));

$pages = [
    new Class implements PageInterface{},
    new Class implements PageInterface{},
];

// add two pages
$container->addPages($pages);

// remove existing pages and add the given pages
$container->setPages($pages);
```

### Removing pages

Removing pages can be done with `removePage()` or `removePages()`.
`removePage()` accepts an instance of a page or an integer. Integer arguments
correspond to the `order` a page has. `removePages()` will remove all pages in
the container.

```php
use Mimmi20\Mezzio\Navigation\Navigation;

$navigationConfig = $serviceLocator->get(NavigationConfigInterface::class);
$navigationConfig->setPages([
    [
        'label'  => 'Page 1',
        'action' => 'page1',
    ],
    [
        'label'  => 'Page 2',
        'action' => 'page2',
        'order'  => 200,
    ],
    [
        'label'  => 'Page 3',
        'action' => 'page3',
    ],
]);
$container = $serviceLocator->get(Navigation::class);

// remove page by implicit page order
$container->removePage(0);      // removes Page 1

// remove page by instance
$page3 = $container->findOneByAction('page3');
$container->removePage($page3); // removes Page 3

// remove page by explicit page order
$container->removePage(200);    // removes Page 2

// remove all pages
$container->removePages();      // removes all pages
```

#### Remove a page recursively

Removing a page recursively can be done with the second parameter of
the `removePage()` method, which expects a `boolean` value.

```php
use Mimmi20\Mezzio\Navigation\Navigation;

$navigationConfig = $serviceLocator->get(NavigationConfigInterface::class);
$navigationConfig->setPages(
    [
        [
            'label' => 'Page 1',
            'route' => 'page1',
            'pages' => [
                [
                    'label' => 'Page 1.1',
                    'route' => 'page1/page1-1',
                    'pages' => [
                        [
                            'label' => 'Page 1.1.1',
                            'route' => 'page1/page1-1/page1-1-1',
                        ],
                    ],
                ],
            ],
        ],
    ]
);
$container = $serviceLocator->get(Navigation::class);

// Removes Page 1.1.1
$container->removePage(
    $container->findOneBy('route', 'page1/page1-1/page1-1-1'),
    true
);
```

### Finding pages

Containers have two finder methods for retrieving pages. Each recursively
searches the container testing for properties with values that match the one
provided.

- `findOneBy($property, $value) : PageInterface|null`: Returns the first page
  found matching the criteria, or `null` if none was found.
- `findAllBy($property, $value) : array<PageInterface>`: Returns an array of all
  page instances matching the criteria.

> Unlike in [Laminas Navigation](https://github.com/laminas/laminas-navigation) the `findBy` is not available.

The finder methods can also be used magically by appending the property name to
`findBy`, `findOneBy`, or `findAllBy`. As an example, `findOneByLabel('Home')`
will return the first matching page with label 'Home'.

Other combinations include `findByLabel(...)`, `findOneByTitle(...)`,
`findAllByController(...)`, etc. Finder methods also work on custom properties,
such as `findByFoo('bar')`.

```php
use Mimmi20\Mezzio\Navigation\Navigation;

$navigationConfig = $serviceLocator->get(NavigationConfigInterface::class);
$navigationConfig->setPages([
    [
        'label' => 'Page 1',
        'uri'   => 'page-1',
        'foo'   => 'bar',
        'pages' => [
            [
                'label' => 'Page 1.1',
                'uri'   => 'page-1.1',
                'foo'   => 'bar',
            ],
            [
                'label' => 'Page 1.2',
                'uri'   => 'page-1.2',
                'class' => 'my-class',
            ],
            [
                'type'   => 'uri',
                'label'  => 'Page 1.3',
                'uri'    => 'page-1.3',
            ],
        ],
    ],
    [
        'label'      => 'Page 2',
        'id'         => 'page_2_and_3',
        'class'      => 'my-class',
        'route'      => 'index1',
    ],
    [
        'label'      => 'Page 3',
        'id'         => 'page_2_and_3',
        'route'      => 'index2',
    ],
]);
$container = $serviceLocator->get(Navigation::class);

// The 'id' is not required to be unique, but be aware that
// having two pages with the same id will render the same id attribute
// in menus and breadcrumbs.

// Returns "Page 2":
$found = $container->findBy('id', 'page_2_and_3');

// Returns "Page 2":
$found = $container->findOneBy('id', 'page_2_and_3');

// Returns "Page 2":
$found = $container->findOneById('page_2_and_3');

// Returns "Page 2" AND "Page 3":
$found = $container->findAllById('page_2_and_3');

// Find all pages matching the CSS class "my-class":
// Returns "Page 1.2" and "Page 2":
$found = $container->findAllBy('class', 'my-class');
$found = $container->findAllByClass('my-class');

// Find first page matching CSS class "my-class":
// Returns "Page 1.2":
$found = $container->findOneByClass('my-class');

// Find all pages matching the CSS class "non-existent":
// Returns an empty array.
$found = $container->findAllByClass('non-existent');

// Find first page matching the CSS class "non-existent":
// Returns null.
$found = $container->findOneByClass('non-existent');

// Find all pages with custom property 'foo' = 'bar':
// Returns "Page 1" and "Page 1.1":
$found = $container->findAllBy('foo', 'bar');

// To achieve the same magically, 'foo' must be in lowercase.
// This is because 'foo' is a custom property, and thus the
// property name is not normalized to 'Foo':
$found = $container->findAllByfoo('bar');

// Find all with controller = 'index':
// Returns "Page 2" and "Page 3":
$found = $container->findAllByController('index');
```

### Iterating containers

`Mezzio\Navigation\ContainerInterface` extends `RecursiveIterator`.  iterate a
container recursively, use the `RecursiveIteratorIterator` class.

```php
use RecursiveIteratorIterator;
use Mimmi20\Mezzio\Navigation\Navigation;

/*
 * Create a container from an array
 */
$navigationConfig = $serviceLocator->get(NavigationConfigInterface::class);
$navigationConfig->setPages([
    [
        'label' => 'Page 1',
        'uri'   => '#',
    ],
    [
        'label' => 'Page 2',
        'uri'   => '#',
        'pages' => [
            [
                'label' => 'Page 2.1',
                'uri'   => '#',
            ],
            [
                'label' => 'Page 2.2',
                'uri'   => '#',
            ],
        ],
    ],
    [
        'label' => 'Page 3',
        'uri'   => '#',
    ],
]);
$container = $serviceLocator->get(Navigation::class);

// Iterate flat using regular foreach:
// Output: Page 1, Page 2, Page 3
foreach ($container as $page) {
    echo $page->label;
}

// Iterate recursively using RecursiveIteratorIterator
$it = new RecursiveIteratorIterator(
    $container,
    RecursiveIteratorIterator::SELF_FIRST
);

// Output: Page 1, Page 2, Page 2.1, Page 2.2, Page 3
foreach ($it as $page) {
    echo $page->label;
}
```

### Other operations

#### hasPage

```php
hasPage(PageInterface $page) : bool
```

Check if the container has the given page.

#### hasPages

```php
hasPages() : bool
```

Checks if there are any pages in the container, and is equivalent to
`count($container) > 0`.

#### toArray

```php
toArray() : array
```

Converts the container and the pages in it to a (nested) array. This can be useful
for serializing and debugging.

```php
use Mimmi20\Mezzio\Navigation\Navigation;

$navigationConfig = $serviceLocator->get(NavigationConfigInterface::class);
$navigationConfig->setPages([
    [
        'label' => 'Page 1',
        'uri'   => '#',
    ],
    [
        'label' => 'Page 2',
        'uri'   => '#',
        'pages' => [
            [
                'label' => 'Page 2.1',
                'uri'   => '#',
            ],
            [
                'label' => 'Page 2.2',
                'uri'   => '#',
            ],
        ],
    ],
]);
$container = $serviceLocator->get(Navigation::class);

var_dump($container->toArray());

/* Output:
array(2) {
  [0]=> array(15) {
    ["label"]=> string(6) "Page 1"
    ["id"]=> NULL
    ["class"]=> NULL
    ["title"]=> NULL
    ["target"]=> NULL
    ["rel"]=> array(0) {
    }
    ["rev"]=> array(0) {
    }
    ["order"]=> NULL
    ["resource"]=> NULL
    ["privilege"]=> NULL
    ["active"]=> bool(false)
    ["visible"]=> bool(true)
    ["type"]=> string(23) "Mezzio\Navigation\Page\Uri"
    ["pages"]=> array(0) {
    }
    ["uri"]=> string(1) "#"
  }
  [1]=> array(15) {
    ["label"]=> string(6) "Page 2"
    ["id"]=> NULL
    ["class"]=> NULL
    ["title"]=> NULL
    ["target"]=> NULL
    ["rel"]=> array(0) {
    }
    ["rev"]=> array(0) {
    }
    ["order"]=> NULL
    ["resource"]=> NULL
    ["privilege"]=> NULL
    ["active"]=> bool(false)
    ["visible"]=> bool(true)
    ["type"]=> string(23) "Mezzio\Navigation\Page\Uri"
    ["pages"]=> array(2) {
      [0]=> array(15) {
        ["label"]=> string(8) "Page 2.1"
        ["id"]=> NULL
        ["class"]=> NULL
        ["title"]=> NULL
        ["target"]=> NULL
        ["rel"]=> array(0) {
        }
        ["rev"]=> array(0) {
        }
        ["order"]=> NULL
        ["resource"]=> NULL
        ["privilege"]=> NULL
        ["active"]=> bool(false)
        ["visible"]=> bool(true)
        ["type"]=> string(23) "Mezzio\Navigation\Page\Uri"
        ["pages"]=> array(0) {
        }
        ["uri"]=> string(1) "#"
      }
      [1]=>
      array(15) {
        ["label"]=> string(8) "Page 2.2"
        ["id"]=> NULL
        ["class"]=> NULL
        ["title"]=> NULL
        ["target"]=> NULL
        ["rel"]=> array(0) {
        }
        ["rev"]=> array(0) {
        }
        ["order"]=> NULL
        ["resource"]=> NULL
        ["privilege"]=> NULL
        ["active"]=> bool(false)
        ["visible"]=> bool(true)
        ["type"]=> string(23) "Mezzio\Navigation\Page\Uri"
        ["pages"]=> array(0) {
        }
        ["uri"]=> string(1) "#"
      }
    }
    ["uri"]=> string(1) "#"
  }
}
*/
```

## Pages

mezzio-navigation ships with two page types:

- [Route pages](#route-pages), using the class `Mezzio\Navigation\Page\Route`
- [URI pages](#uri-pages), using the class `Mezzio\Navigation\Page\Uri`

Route pages link to on-site web pages, and are defined using Route parameters
(`route`, `params`). URI pages are defined by a single
property `uri`, which give you the full flexibility to link off-site pages or do
other things with the generated links (e.g. a URI that turns into `<a href="#">foo<a>`).

> Route Pages replace the MVC Pages from [Laminas Navigation](https://github.com/laminas/laminas-navigation).
> 
> Unlike in [Laminas Navigation](https://github.com/laminas/laminas-navigation) the options `controller` and `action`
> are not supported, 

### Common page features

All page classes must extend `Mezzio\Navigation\Page\PageInterface`, and will thus
share a common set of features and properties. Most notably, they share the
options in the table below and the same initialization process.

Option keys are mapped to `set*()` methods. This means that the option `order` maps to the method
`setOrder()`, and `reset_params` maps to the method `setResetParams()`. If there is no setter
method for the option, it will be set as a custom property of the page.

Read more on extending `Mezzio\Navigation\Page\PageInterface` in the section
["Creating custom page types"](#creating-custom-page-types).

### Common page options

| Key       | Type                        | Default | Description                                                                                                                                                                                                                                                                                                                                                                                                                                                 |
|-----------|-----------------------------|---------|-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| label     | `string`                    | `NULL`  | A page label, such as 'Home' or 'Blog'.                                                                                                                                                                                                                                                                                                                                                                                                                     |
| fragment  | `string\|null`              | `NULL`  | A fragment identifier (anchor identifier) pointing to an anchor within a resource that is subordinate to another, primary resource. The fragment identifier introduced by a hash mark "#". Example: ``http://www.example.org/foo.html#bar`` (*bar* is the fragment identifier)                                                                                                                                                                              |
| id        | `string\|integer`           | `NULL`  | An *id* tag/attribute that may be used when rendering the page, typically in an anchor element.                                                                                                                                                                                                                                                                                                                                                             |
| class     | `string`                    | `NULL`  | A *CSS* class that may be used when rendering the page, typically in an anchor element.                                                                                                                                                                                                                                                                                                                                                                     |
| liClass   | `string`                    | `NULL`  | A *CSS* class that may be used when rendering the page, typically in an li element around the anchor element.                                                                                                                                                                                                                                                                                                                                               |
| title     | `string`                    | `NULL`  | A short page description, typically for using as the title attribute in an anchor.                                                                                                                                                                                                                                                                                                                                                                          |
| target    | `string`                    | `NULL`  | Specifies a target that may be used for the page, typically in an anchor element.                                                                                                                                                                                                                                                                                                                                                                           |
| rel       | `array`                     | `[]`    | Specifies forward relations for the page. Each element in the array is a key-value pair, where the key designates the relation/link type, and the value is a pointer to the linked page. An example of a key-value pair is ``'alternate' => 'format/plain.html'``. To allow full flexibility, there are no restrictions on relation values. The value does not have to be a string. Read more about ``rel`` and ``rev`` in the section on the Links helper. |
| rev       | `array`                     | `[]`    | Specifies reverse relations for the page. Works exactly like rel.                                                                                                                                                                                                                                                                                                                                                                                           |
| order     | `string\|integer\|null`     | `NULL`  | Works like order for elements in ``Laminas\Form``. If specified, the page will be iterated in a specific order, meaning you can force a page to be iterated before others by setting the order attribute to a low number, e.g. -100. If a String is given, it must parse to a valid int. If ``NULL`` is given, it will be reset, meaning the order in which the page was added to the container will be used.                                               |
| privilege | `string\|null`              | `NULL`  | privilege to associate with the page. Read more in the section on ACL integration in view helpers.                                                                                                                                                                                                                                                                                                                                                          |
| active    | `boolean`                   | `FALSE` | Whether the page should be considered active for the current request. If active is FALSE or not given, MVC pages will check its properties against the request object upon calling ``$page->isActive()``.                                                                                                                                                                                                                                                   |
| visible   | `boolean`                   | `TRUE`  | Whether page should be visible for the user, or just be a part of the structure. Invisible pages are skipped by view helpers.                                                                                                                                                                                                                                                                                                                               |
| pages     | `array\|Travsersable\|null` | `NULL`  | Child pages of the page. This could be an array or `Traversable` object containing either page options that can be passed to the `factory()` method, `PageInterface` instances, or a mixture of both.                                                                                                                                                                                                                                                       |

> #### Custom properties
>
> All pages support setting and retrieval of custom properties by use of the
> magic methods `__set($name, $value)`, `__get($name)`, `__isset($name)` and
> `__unset($name)`. Custom properties may have any value, and will be included
> in the array that is returned from `$page->toArray()`, which means that pages
> can be serialized/deserialized successfully even if the pages contains
> properties that are not native in the page class.
>
> Both native and custom properties can be set using `$page->set($name, $value)`
> and retrieved using `$page->get($name)`, or by using magic methods.
> 
> The following example demonstrates custom properties:
>
> ```php
> $page = new Mimmi20\Mezzio\Navigation\Page\Route();
> $page->foo     = 'bar';
> $page->meaning = 42;
>
> echo $page->foo;
>
> if ($page->meaning != 42) {
>     // action should be taken
> }
> ```

### Route pages

Routes can be used with Route pages. If a page has a route, this route will be
used in `getHref()` to generate `href` attributes, and the `isActive()` method will compare the
`Mezzio\Router\RouteResult` params with the page's params to determine if the page
is active.

#### useRouteMatch flag

If you want to re-use any matched route parameters
when generating a link, you can do so via the `useRouteMatch` flag. This is
particularly useful when creating segment routes that include the currently
selected language or locale as an initial segment, as it ensures the links
generated all include the matched value.

#### Route page options

| Key           | Type                            | Default | Description                                                                                    |
|---------------|---------------------------------|---------|------------------------------------------------------------------------------------------------|
| params        | `array`                         | `[]`    | User params to use when generating `href` to the page.                                         |
| route         | `string`                        | `NULL`  | Route name to use when generating `href` to the page.                                          |
| routeMatch    | `Mezzio\Router\RouteResult`     | `NULL`  | `RouteInterface` matches used for routing parameters and testing validity.                     |
| useRouteMatch | `boolean`                       | `FALSE` | If true, then the `getHref()` method will use the `routeMatch` parameters to assemble the URI. |
| router        | `Mezzio\Router\RouterInterface` | `NULL`  | Router for assembling URLs.                                                                    |
| query         | `array`                         | `[]`    | Query string arguments to use when generating `href` to page.                                  |

#### isActive() determines if page is active

This example demonstrates that Route pages determine whether they are active by
using the params found in the route match object.

```php
use Mimmi20\Mezzio\Navigation\Page;

/**
 * Dispatched request:
 * - route:     index
 */
$page1 = new Page\Route([
    'route' => 'index',
]);

$page2 = new Page\Route([
    'route' => 'edit',
]);

$page1->isActive(); // returns true
$page2->isActive(); // returns false

/**
 * Dispatched request:
 * - route:      edit
 * - id:         1337
 */
$page = new Page\Route([
    'route'  => 'edit',
    'params' => ['id' => 1337],
]);

// returns true, because request has the same route
$page->isActive();

/**
 * Dispatched request:
 * - route:     edit
 */
$page = new Page\Route([
    'route'  => 'edit',
    'params' => ['id' => null],
]);

// returns false, because page requires the id param to be set in the request
$page->isActive(); // returns false
```

### URI Pages

Pages of type `Mezzio\Navigation\Page\Uri` can be used to link to pages on other
domains or sites, or to implement custom logic for the page. In addition to the
common page options, a URI page takes only one additional option, a `uri`. The
`uri` will be returned when calling `$page->getHref()`, and may be a `string` or
`null`.

> ### No auto-determination of active status
>
> `Mezzio\Navigation\Page\Uri` will not try to determine whether it should be
> active when calling `$page->isActive()`; it merely returns what currently is
> set. In order to make a URI page active, you must manually call
> `$page->setActive()` or specify the `active` as a page option during
> instantiation.

#### URI page options

| Key      | Type           | Default | Description                                                                                   |
|----------|----------------|---------|-----------------------------------------------------------------------------------------------|
| uri      | `string`       | `NULL`  | URI to page. This can be any string or `NULL`.                                                |
| resource | `string\|null` | `NULL`  | resource to associate with the page. Read more in the section on integration in view helpers. |

### Creating custom page types

When implementing `Mezzio\Navigation\Page\PageInterface` and using the `Mezzio\Navigation\Page\PageTrait`, there is usually no need to
override the constructor or the `setOptions()` method. The page constructor
takes a single parameter, an `iterable`, which is then
passed to `setOptions()`. That method will in turn call the appropriate `set*()`
methods based on the options provided, which in turn maps the option to native
or custom properties. If the option `internal_id` is given, the method will
first look for a method named `setInternalId()`, and pass the option to this
method if it exists. If the method does not exist, the option will be set as a
custom property of the page, and be accessible via `$internalId =
$page->internal_id;` or `$internalId = $page->get('internal_id');`.

#### Basic custom page example

The only thing a custom page class needs to implement is the `getHref()` method.

```php
namespace My;

use Mimmi20\Mezzio\Navigation\Page\PageInterface;
use Mimmi20\Mezzio\Navigation\Page\PageTrait;

class Page implements PageInterface
{
    use PageTrait;
    public function getHref(): string
    {
        return 'something-completely-different';
    }
}
```

#### A custom page with properties

When adding properties to an extended page, there is no need to override/modify
`setOptions()`.

```php
namespace My\Navigation;

use Mimmi20\Mezzio\Navigation\Page\PageInterface;

class Page implements PageInterface
{
    protected $foo;
    protected $fooBar;

    public function setFoo($foo)
    {
        $this->foo = $foo;
    }

    public function getFoo()
    {
        return $this->foo;
    }

    public function setFooBar($fooBar)
    {
        $this->fooBar = $fooBar;
    }

    public function getFooBar()
    {
        return $this->fooBar;
    }

    public function getHref(): string
    {
        return sprintf('%s/%s', $this->foo, $this->fooBar);
    }
}

// Instantiation:
$page = new Page([
    'label'   => 'Property names are mapped to setters',
    'foo'     => 'bar',
    'foo_bar' => 'baz',
]);
```

### Creating pages using the page factory

All pages (also custom classes), can be created using the page factory,
`Mezzio\Navigation\Page\PageFactory`. The factory accepts either an
`iterable` set of options.  Each key in the options corresponds to a
page option, as seen earlier.  If the option `uri` is given and no Route options
are provided (`route`), a URI page will be
created. If any of the Route options are given, an Route page will be created.

If `type` is given, the factory will assume the value to be the name of the
class that should be created. If the value is `route` or `uri`, an Route or URI page
will be created, respectively.

#### Creating an Route page using the page factory

```php
use Mimmi20\Mezzio\Navigation\Page\PageInterface;

// Route page, as "route" is defined
$page = (new PageFactory())->factory([
    'label' => 'Home',
    'route' => 'home',
]);

// Route page, as "type" is "route"
$page = (new PageFactory())->factory([
    'type'   => 'route',
    'label'  => 'My Route page',
]);
```

#### Creating a URI page using the page factory

```php
use Mimmi20\Mezzio\Navigation\Page\PageInterface;

// URI page, as "uri" is present, with now MVC options
$page = (new PageFactory())->factory([
    'label' => 'My URI page',
    'uri'   => 'http://www.example.com/',
]);

// URI page, as "uri" is present
$page = (new PageFactory())->factory([
    'label'  => 'Search',
    'uri'    => 'http://www.example.com/search',
    'active' => true,
]);

// URI page, as "uri" is present
$page = (new PageFactory())->factory([
    'label' => 'My URI page',
    'uri'   => '#',
]);

// URI page, as "type" is "uri"
$page = (new PageFactory())->factory([
    'type'  => 'uri',
    'label' => 'My URI page',
]);
```

#### Creating a custom page type using the page factory

To create a custom page type using the factory, use the option `type` to specify
a class name to instantiate.

```php
namespace My\Navigation;

use Mimmi20\Mezzio\Navigation\Page\PageInterface;

class Page extends PageInterface
{
    protected $fooBar = 'ok';

    public function setFooBar($fooBar)
    {
        $this->fooBar = $fooBar;
    }
}

// Creates Page instance, as "type" refers to its class.
$page = (new PageFactory())->factory([
    'type'    => Page::class,
    'label'   => 'My custom page',
    'foo_bar' => 'foo bar',
]);
```

## Quick Start

### Usage in a mezzio-based application

The fastest way to get up and running with mezzio-navigation is:

- Register mezzio-navigation or use the Component Installer.
- Add the Middleware to the Pipeline.
- Define navigation container configuration under the top-level `navigation` key
  in your application configuration.
- Render your container using a navigation view helper within your view scripts.

#### Register mezzio-navigation

Edit the application configuration file `config/config.php`:

```php
<?php
// ...
$aggregator = new ConfigAggregator(
    [
        // ...
        \Mimmi20\Mezzio\Navigation\ConfigProvider::class, // <-- Add this line
        // ...
    ],
    $cacheConfig['config_cache_path'],
);
```

#### Add the NavigationMiddleware to the pipeline

```php
<?php
return [
    'middleware' => [
        'Mezzio\Authentication\AuthenticationMiddleware', // <-- not required
        'Mimmi20\Mezzio\Navigation\NavigationMiddleware', // <-- Add this line
        // ...
    ],
];
```

If you need the Navigation inside the Layout, and the Layout is used also for the Not-Found-Page, you have to add the Middleware in the Pipeline before the Routing.

```php
    $app->pipe(\Mimmi20\Mezzio\Navigation\NavigationMiddleware::class); // <-- Add this line

    // Register the routing middleware in the middleware pipeline.
    // This middleware registers the Mezzio\Router\RouteResult request attribute.
    $app->pipe(RouteMiddleware::class);
```

#### Navigation container configuration

Add the container definition to your configuration file, e.g.
`config/autoload/global.php`:

```php
<?php
return [
    // ...

    'navigation' => [
        'default' => [
            [
                'label' => 'Home',
                'route' => 'home',
            ],
            [
                'label' => 'Page #1',
                'route' => 'page-1',
                'pages' => [
                    [
                        'label' => 'Child #1',
                        'route' => 'page-1-child',
                    ],
                ],
            ],
            [
                'label' => 'Page #2',
                'route' => 'page-2',
            ],
        ],
    ],
    // ...
];
```

#### Render the navigation

Mezzio support multiple view renders. 

One renderer is available:

- [mezzio-navigation-laminasviewrenderer](https://github.com/mimmi20/mezzio-navigation-laminasviewrenderer/),
  which implements ([Mezzios LaminasViewRenderer](https://github.com//mezzio/mezzio-laminasviewrenderer))

## License

This package is licensed using the MIT License.

Please have a look at [`LICENSE.md`](LICENSE.md).
