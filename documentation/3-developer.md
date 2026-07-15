# Developing

## Modular design

VelnoxAICMS uses the popular [Laravel Modules](https://github.com/nWidart/laravel-modules) package which provides a flexible architecture. All modules are locates in the `Modules` directory. Our recommendation is that if you plan to make changes to the app, create your own modules so that future updates will be seamless and will not conflict with changes you have made to the core modules. Consult the [Laravel Modules](https://laravelmodules.com/) documentation on the commands to use to generate and manage modules.

We have also customized Inertia JS to look for view files in modules, so all views that relate to a particular module should be places inside the `resources/views` folder.

In the controller, to reference a view file that is inside a Module, you have to prefix it with the Module name like this:

```php{5}
public function index()
{
    $modelCollection = Model::query()->get();

    Inertia::render('MyModule::index', [
        'data' => SomeModelData::collect($modelCollection)
    ]);
}
```

The above will look for the file `resources/views/index.vue` inside the module named `MyModule`.

However, you are still free to place Inertia page views inside `resources/js/pages` directory just like in any other Inertia.js app. In fact, VelnoxAICMS uses this directory to serve frontend pages.

## Data Transfer Objects

VelnoxAICMS uses Data Transfer Objects to serve data to the frontend. You can generate a new Data Transfer Object inside a module (eg Pages) using the following command:

```bash:no-line-numbers
php artisan builder:make-data PageData Page
```

In this case, `PageData` is the name of the DTO class while `Page` is the module you want to create the DTO in. DTO classes are created in module's `app/Data` directory.

Data classes extend [Spatie's Laravel Data package](https://spatie.be/docs/laravel-data/v4/introduction) and they are decorated with a TypeScript decorator. This decorator transforms the DTO to a TypeScript type and writes it out to the frontend. So these types can be used in frontend scripts and Vue components. for instance, the above `PageData` can be referenced in the frontend as follows:

The `PageData.php` file may look like this:

```php{9} title="PageData.php"
<?php

namespace Modules\Page\Data;

use Spatie\LaravelData\Data;
use Modules\Page\Models\Page;
use Spatie\LaravelData\Optional;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;

#[TypeScript()] // <--- Typescript transformer
class PageData extends Data
{
    public function __construct(
        public string $id,
        public string|Optional $type,
        public string $slug,
        public string $title
    ) {}

    public static function fromModel(Page $page): self
    {
        return new self(
            id: $page->id,
            type: 'page',
            slug: $page->slug,
            title: $page->title
        );
    }
}
```

In the `PageController.php` file, we have the following index function to list pages:

```php{6} title="PageController.php"
public function index()
{
    $pageData = Page::query()->get();

    Inertia::render('Pages::index', [
        'pages' => PageData::collect($pageData)
    ]);
}
```
and then in the `index.vue` file, the prop can be typed as follows:

```ts{8} title="index.vue"
<template>

</template>

<script setup lang="ts">

defineProps<{
    pages: Modules.Pages.Data.PageData[] // <-- This becomes Typed.
}>()

</script>
```

## Actions
VelnoxAICMS makes heavy use of Actions. The intention is to keep controller as light as possible and extract database interaction to Actions. This also has the benefit that Actions can be called from different classes and even across modules, so we do not have to repeat the same query in different places.

`Laravel Modules` package comes with a generator for actions which can be called as follows:
```bash:no-line-numbers
php artisan module:make-action GetAllPagesAction Pages
```

This will generate an action class called `app/Actions/GetAllPagesAction.php` in the `Pages` module. The action can then be called in a controller (or anywhere else) as follows:


```php{3} title="PageController.php"
public function index()
{
    $pageData = app(GetAllPagesAction::class)->handle();

    Inertia::render('Pages::index', [
        'pages' => PageData::collect($pageData)
    ]);
}
```

## Create new Builder components
VelnoxAICMS comes with some pre-defined, draggable components. However, you are not limited to this set of components. You can build your own components by just looking at how the core components are designed. That is the beauty of OpenSource.

VelnoxAICMS makes it easy to generate a new Builder component through a single command:

```bash:no-line-numbers
php artisan builder:make
```

This will launch an interactive prompt where you can provide a name for the new component, and the category you want it to be created in (can be container, form, media, etc)

Basically, to create a draggable component, 3 files are required - `config.ts`, `settings.ts`, `render.vue` and `mycomponent.vue`.

* `config.ts` - this contains configurable settings relate to the component, including styles, name, etc. The `id` has to be unique and must match the name of the directory and component of the custom element.
* `settings.ts` - this identifies the settings that should be rendered on the right sidebar of the Builder when the component is selected in the canvas.
* `mycomponent.vue` - this is the actual component and is the version rendered in the builder. So it includes all the markers required for building the interface (eg draggable events, builder outlines etc). NB: `mycomponent.vue` is just a placeholder name. The actual name will be the name you actually want to call your component and should be unique across the entire application.
* `render.vue` - this is like `mycomponent.vue`, but is the version that will be rendered to the frontend for end users. It excludes all draggable events. This is really not mandatory, but highly recommended. If it is absent, `mycomponent.vue` will be used to render to the frontend instead (please avoid doing this!).


Your builder components can do pretty much anythin you want...you are only limited by your knowledge of Vue and Laravel. We have provided examples of `Testimonial` and `Form` components that show how you can build components that interact with the backend at runtime. These are created as examples for you to follow in building your own components. However, we intend to develop more components in future versions.