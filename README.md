# Verde

<p align="center">
    <img src="https://raw.githubusercontent.com/verdephp/verde/master/art/assert-example.png" width="600" alt="Verde">
    <img src="https://raw.githubusercontent.com/verdephp/verde/master/art/expect-example.png" width="600" alt="Assertion failed example">
    <p align="center">
        <a href="https://github.com/verdephp/verde/actions">
            <img alt="GitHub Workflow Status (master)" src="https://img.shields.io/github/workflow/status/verdephp/verde/Tests/master" />
        </a>
    </p>
</p>

## a BDD Style Library for your PHP Tests

**Verde** is a composer library, inspired to Jasmine and Jest, for writing tests with [PHPUnit](https://phpunit.de/) and [pest](https://pestphp.com/) 
using BDD style syntax, and nicer exceptions messages.

It also offers [mocks](https://verdephp.github.io/en/mocks.html) and [spies](https://verdephp.github.io/en/spies.html) thanks to the power of [runkit7](https://github.com/runkit7/runkit7).

## Getting Started

```sh
composer require "verdephp/verde" --dev
```

Then start writing your tests:

```php
<?php

use function Verde\expect;

test('the Answer to Everything', function() {
    expect(getTheAnswer())->toBe(42);
});

// or with PHPUnit:
class SimpleTest extends TestCase
{
    public function test_the_answer_to_everything()
    {
        expect(getTheAnswer())->toBe(42);
    }
}
```

### Spy

Easy and clear syntax inspired to Jest and Jasmine

```php
<?php
use function Verde\expect;

test('retrieves the ingredients first and then bake the pizza', function () {
    $spyGetPizzaIngredients = spyOn('getPizzaIngredients');
    $spyBakePizza = spyOn('bakePizza');

    // We don't want to make the HTTP request
    $spyGetPizzaIngredients->mockReturnValue(['Mozzarella', 'Pomodoro']);

    makePizza('Margherita');

    // Here we make sure that the functions are called in the right order
    expect($spyGetPizzaIngredients)->toHaveBeenCalledBefore($spyBakePizza);
    
    // We can also check the arguments passed
    expect($spyGetPizzaIngredients)->toHaveBeenCalledWith('Margherita');
    expect($spyBakePizza)->toHaveBeenCalledWith(['Mozzarella', 'Pomodoro']);
});
```

**NOTE**: Spies require [runkit7](https://github.com/runkit7/runkit7) to work!

### Mocks

Easy to use syntax for mocking class

```php
<?php

use \Verde\expect;
use \Verde\func;

function theAnswerToEverything(callable $callback) {
    $callback(42);
}

test('the mock function is called with the correct argument', function() {
    $mockFunction = func();
    
    theAnswerToEverything($mockFunction->getCallable());

    // We can spy on the mock to see if it has been called and with which argument    
    expect($mockFunction)->toHaveBeenCalledWith(42);
})
```

**NOTE**: Spies require [runkit7](https://github.com/runkit7/runkit7) to work!

## Documentation

Checkout [the documentation](https://verdephp.github.io)

## Contributing

Please read the CONTRIBUTING.md file.

## Why Verde?

Because being mainly a JavaScript developer, I find more straightforward the Jest/Jasmine syntax over the assert one of PHPUnit.

## Why runkit?

To make the spying and mocking experience as simple as the JavaScript world one.

**Note**: Runkit is needed by the `mock` and `spy` helpers only.

---

Verde is open-sourced software by [Ceceppa](https://twitter.com/ceceppa) licensed under the [MIT License](https://github.com/verdephp/verde/blob/master/LICENSE.md).
