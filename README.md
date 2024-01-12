# Laravel Nova Text Template

Support laravel nova 3.

![](https://github.com/ZakariaTlilani/text-template/blob/master/preview/1.gif)
![](https://github.com/ZakariaTlilani/text-template/blob/master/preview/2.gif)

## Installation

``` php
composer require zakariatlilani/text-template
```

## Usage

``` php
use zakariatlilani\texttemplate\TextTemplate;

    TextTemplate::make('Name')
        ->category('category')  // the category related to the text template
        ->endpoint('/api/loadData/{category}/product/{product}'), // the endpoint related to the template (a product in this scenario)

```
i recommend using it with this package: <a href="https://github.com/outl1ne/nova-simple-repeatable">nova-simple-repeatable</a>.

## Credits
<ul dir="auto">
<li><a href="https://github.com/MohamedMaher5">Mohamed Maher</a></li>
<li><a href="https://github.com/ZakariaTlilani">Zakaria Tlilani</a></li>
</ul>

## License
Nova Text template is open-sourced software licensed under the <a href="https://opensource.org/licenses/MIT">MIT license</a>.