# BehatParser

A simple library for parsing and searching in Behat contexts.

The intention of this library is to create autocomplete functions within the php language server using the language server protocol developer by Microsoft.

However, this could be usefull in other scenario's as well.

## Example

To fetch the step defenitions for a given class:

```php
$behat_parser_library = new Parser();
$class = \tests\FeatureContext::class;
$behat_parser_library->readClassForStepDefinitions($class);
$available_steps = $behat_parser_library->getAllStepDefinitions();
```

You can also use an array of classes:

```php
$behat_parser_library = new Parser();
$classes = [
  \tests\FeatureContext::class,
  \tests\CustomContext::class,
];
$behat_parser_library->readClassesForStepDefinitions($class);
$available_steps = $behat_parser_library->getAllStepDefinitions();
```

Then you can invoke the matcher to search for specific steps:

```php
$behat_parser_matcher = new Matcher($behat_parser_library);
$matching_result = $behat_parser_matcher->findStepContaining('I am using');
```
