<?php
use BehatParser\Parser;

/**
 * Tests the basic parsing methods.
 */
class BehatParserClassParsingTest extends PHPUnit_Framework_TestCase
{

    public function testLibraryLoading(): void
    {
        $behat_parser_library = new Parser();
        self::assertInstanceOf(Parser::class, $behat_parser_library);
    }

    public function testClassLoader(): void
    {
        $behat_parser_library = new Parser();
        $class = \BehatParserTest\Fixtures\FeatureContextWithBasicStepAnnotation::class;
        $behat_parser_library->readClassForStepDefinitions($class);
        self::assertEquals(
            [
                'BehatParserTest\Fixtures\FeatureContextWithBasicStepAnnotation',
            ],
            $behat_parser_library->getClasses()
        );
    }

    public function testClassLoaderAvoidDuplicates(): void
    {
        $behat_parser_library = new Parser();
        $class = \BehatParserTest\Fixtures\FeatureContextWithBasicStepAnnotation::class;
        $behat_parser_library->readClassForStepDefinitions($class);
        $behat_parser_library->readClassForStepDefinitions($class);
        self::assertCount(1, $behat_parser_library->getClasses());
    }

    public function testParseEmptyStep(): void
    {
        $behat_parser_library = new Parser();
        $class = \BehatParserTest\Fixtures\FeatureContextWithoutStepAnnotation::class;
        $behat_parser_library->readClassForStepDefinitions($class);
        $step_definitions = $behat_parser_library->getAllStepDefinitions();
        self::assertEmpty($step_definitions);
    }

    public function testParseSimpleStep(): void
    {
        $behat_parser_library = new Parser();
        $class = \BehatParserTest\Fixtures\FeatureContextWithBasicStepAnnotation::class;
        $behat_parser_library->readClassForStepDefinitions($class);
        $step_definitions = $behat_parser_library->getAllStepDefinitions();
        self::assertEquals(
            ['I am using the example step with basic annotation'],
            $step_definitions
        );
    }

    public function testParseMethodWithMultipleSteps(): void
    {
        $behat_parser_library = new Parser();
        $class = \BehatParserTest\Fixtures\FeatureContextWithDoubleStepAnnotation::class;
        $behat_parser_library->readClassForStepDefinitions($class);
        $step_definitions = $behat_parser_library->getAllStepDefinitions();
        self::assertEquals(
            [
                'I have a step definition',
                'I have another step definition',
            ],
            $step_definitions
        );
    }

    public function testAllSimplePatternMatching(): void
    {
        $behat_parser_library = new Parser();
        $class = \BehatParserTest\Fixtures\FeatureContextWithAllPatterns::class;
        $behat_parser_library->readClassForStepDefinitions($class);
        $step_definitions = $behat_parser_library->getAllStepDefinitions();
        self::assertEquals(
            [
                'When definition',
                'But definition',
                'Given definition',
                'Then definition',
            ],
            $step_definitions
        );
    }

    public function testRegexPatternMatching(): void
    {
        $behat_parser_library = new Parser();
        $class = \BehatParserTest\Fixtures\FeatureContextWithRegexPatterns::class;
        $behat_parser_library->readClassForStepDefinitions($class);

        $step_definitions = $behat_parser_library->getAllStepDefinitions();
        self::assertEquals(
            [
                '/^I do something with "([^"]*)"$/',
                'I do something with "([^"]*)"',
                'I add "([^"]*)" to the list',
            ],
            $step_definitions
        );
    }

}
