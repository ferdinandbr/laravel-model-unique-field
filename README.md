# Simple way to handle dynamically unique fields in Laravel models

The ferdinandbr/laravel-model-unique-field package provides a simple way to handle dynamically unique fields in Laravel models. When creating or updating a model with a field that needs to be unique, the package automatically appends a numeric suffix if a duplicate value is detected. This allows you to easily manage unique fields like names, serial numbers, or other attributes without manually checking for duplicates.

For example, if a model is created with a name "Product A" and another model is created with the same name, the second model’s name will automatically be "Product A #2", and so on. This feature is especially useful when dealing with large datasets, imports, or batch operations where conflicts might occur.

The package is customizable and can be applied to any model attribute, ensuring smooth management of unique field values across your application.

## Requirement

- Minimum PHP ^7.3

## Installation

You can install the package via composer for latest version
```bash
$ composer require ferdinandbr/laravel-model-unique-field
```


## Quick usage

Add UniqueField trait to model and add $dynamicField to field referencing the column you want the value to not be repeated
```php

use Ferdinandbr\LaravelModelUniqueField\Traits\UniqueField;

class TestModel extends Model
{
    use UniqueField;

    protected $dynamicField = 'name';

}
```

# Examples

```php

use App\Models\Product;

$product1 = Product::create(['name' => 'Widget']);
$product2 = Product::create(['name' => 'Widget']);
$product3 = Product::create(['name' => 'Widget']);

echo $product1->name; // Output: "Widget"
echo $product2->name; // Output: "Widget #2"
echo $product3->name; // Output: "Widget #3"


use App\Models\Device;

$device1 = Device::create(['serial_number' => 'SN123']);
$device2 = Device::create(['serial_number' => 'SN123']);
$device3 = Device::create(['serial_number' => 'SN123']);

echo $device1->serial_number; // Output: "SN123"
echo $device2->serial_number; // Output: "SN123 #2"
echo $device3->serial_number; // Output: "SN123 #3"

```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.