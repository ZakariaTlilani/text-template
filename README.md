# Laravel Nova Text Template

Support laravel nova 3.

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
