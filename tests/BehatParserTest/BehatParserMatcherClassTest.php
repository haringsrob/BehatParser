<?php
use BehatParser\Matcher;
use BehatParser\Parser;

/**
 * Tests the basic matching methods.
 */
class BehatParserMatcherClassTest extends PHPUnit_Framework_TestCase
{

    public function testBehatParserMatcherInitializer(): void
    {
        $behat_parser_library = new Parser();
        $behat_parser_matcher = new Matcher($behat_parser_library);
        self::assertInstanceOf(Matcher::class, $behat_parser_matcher);
    }

    public function testMatcherSimpleMatchContainsString(): void
    {
        $behat_parser_library = new Parser();
        $behat_parser_matcher = new Matcher($behat_parser_library);
        $class = \BehatParserTest\Fixtures\FeatureContextWithBasicStepAnnotation::class;
        $behat_parser_library->readClassForStepDefinitions($class);

        $matching_result = $behat_parser_matcher->findStepContaining(
            'I am using'
        );
        $this->assertEquals(
            ['I am using the example step with basic annotation'],
            $matching_result
        );
    }

    public function testMatcherMultipleMatchContainsString(): void
    {
        $behat_parser_library = new Parser();
        $behat_parser_matcher = new Matcher($behat_parser_library);
        $class = \BehatParserTest\Fixtures\FeatureContextWithDoubleStepAnnotation::class;
        $behat_parser_library->readClassForStepDefinitions($class);

        $matching_result = $behat_parser_matcher->findStepContaining(
            'I have a step'
        );
        $this->assertEquals(
            ['I have a step definition'],
            $matching_result
        );

        $matching_result = $behat_parser_matcher->findStepContaining(
            'I have'
        );
        $this->assertEquals(
            [
                'I have a step definition',
                'I have another step definition',
            ],
            $matching_result
        );
    }

    public function testMatcherRegexContainsString(): void
    {
        $behat_parser_library = new Parser();
        $behat_parser_matcher = new Matcher($behat_parser_library);
        $class = \BehatParserTest\Fixtures\FeatureContextWithRegexPatterns::class;
        $behat_parser_library->readClassForStepDefinitions($class);

        $matching_result = $behat_parser_matcher->findStepContaining(
            'I do something'
        );
        $this->assertEquals(
            [
                '/^I do something with "([^"]*)"$/',
                'I do something with "([^"]*)"',
            ],
            $matching_result
        );
    }

    public function testMatcherContainsStringNoResult(): void
    {
        $behat_parser_library = new Parser();
        $behat_parser_matcher = new Matcher($behat_parser_library);
        $class = \BehatParserTest\Fixtures\FeatureContextWithDoubleStepAnnotation::class;
        $behat_parser_library->readClassForStepDefinitions($class);

        $matching_result = $behat_parser_matcher->findStepContaining(
            'I have no step'
        );
        $this->assertEmpty($matching_result);
    }

    public function testMatcherMultipleClassesContainString(): void
    {
        $behat_parser_library = new Parser();
        $behat_parser_matcher = new Matcher($behat_parser_library);
        $classes = [
            \BehatParserTest\Fixtures\FeatureContextWithDoubleStepAnnotation::class,
            \BehatParserTest\Fixtures\FeatureContextWithAllPatterns::class,
        ];
        $behat_parser_library->readClassesForStepDefinitions($classes);

        $matching_result = $behat_parser_matcher->findStepContaining(
            'I have'
        );
        $this->assertEquals(
            [
                'I have a step definition',
                'I have another step definition',
            ],
            $matching_result
        );

        $matching_result = $behat_parser_matcher->findStepContaining(
            'definition'
        );
        $this->assertEquals(
            [
                'I have a step definition',
                'I have another step definition',
                'When definition',
                'But definition',
                'Given definition',
                'Then definition',
            ],
            $matching_result
        );
    }

    public function testMatcherWithArgument(): void
    {
        $behat_parser_library = new Parser();
        $behat_parser_matcher = new Matcher($behat_parser_library);
        $class = \BehatParserTest\Fixtures\FeatureContextWithRegexPatterns::class;
        $behat_parser_library->readClassForStepDefinitions($class);

        $matching_result = $behat_parser_matcher->findStepContaining(
            'I add "list item"'
        );
        $this->assertEquals(
            [
                'I add "([^"]*)" to the list',
            ],
            $matching_result
        );
    }
}
