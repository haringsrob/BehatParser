<?php

namespace BehatParser;

class Matcher
{

    private $behatParser;

    /**
     * Matcher constructor.
     *
     * @param \BehatParser\Parser $behatParser
     */
    public function __construct(Parser $behatParser)
    {
        $this->behatParser = $behatParser;
    }

    public function findStepContaining($string): array
    {
        $matching_steps = [];
        $available_step_definitions = $this->behatParser->getAllStepDefinitions(
        );
        foreach ($available_step_definitions as $step_definition) {
            if ($this->checkIfStepContainsString($step_definition, $string)) {
                $matching_steps[] = $step_definition;
            }
        }
        return $matching_steps;
    }

    private function checkIfStepContainsString($step_definition, $search_string): bool
    {
        $this->stripRegexQuotesFromString($step_definition);
        $this->stripRegexQuotesFromString($search_string);
        return strpos($step_definition, $search_string) !== false;
    }

    private function stripRegexQuotesFromString(&$string): void
    {
        $string = preg_replace('/"([^"]*)"/', '""', $string);
    }
}
