## Simple validation for Slim framework

> Validation library for Slim Framework based on [Respect/Validation](https://github.com/Respect/Validation).

## Installation 
    composer require rosamarsky/slim-validation
    
## Usage

```php
$app->post('/users', function(Request $request, Response $response) use ($container) {
    // ...
})->add(validator(
    v::key('email' , v::notEmpty()->email()),
    v::key('password', v::notEmpty()->length(8, null))
));
```
