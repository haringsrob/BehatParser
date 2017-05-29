<?php

namespace BehatParserTest\Fixtures;

class FeatureContextWithRegexPatterns
{

    /**
     * @When /^I do something with "([^"]*)"$/
     * @When I do something with "([^"]*)"
     */
    public function exampleStepWithRegexAnnotation(): void
    {
    }

    /**
     * @Given I add "([^"]*)" to the list
     */
    public function exampleStepWithSingleRegexAnnotation(): void
    {
    }
}
