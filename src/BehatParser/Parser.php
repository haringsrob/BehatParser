<?php

namespace BehatParser;

class Parser
{

    // @todo: Regex works, but needs improvement.
    const DOCBLOCK_STEP_PATTERN = '/(?:@When|@But|@Given|@Then){1}(?:\s){1}(.+)/';

    private $classes = [];

    private $availableStepsList = [];

    /**
     * @param $class
     */
    public function readClassForStepDefinitions($class): void
    {
        if (!$this->checkIfClassIsAlreadyInClasses($class)) {
            $this->classes[] = $class;
        }
    }

    public function readClassesForStepDefinitions(array $classes): void
    {
        foreach ($classes as $class) {
            $this->readClassForStepDefinitions($class);
        }
    }

    /**
     * @param $class
     *
     * @return bool
     */
    private function checkIfClassIsAlreadyInClasses($class): bool
    {
        if (in_array($class, $this->classes, true)) {
            return true;
        }
        return false;
    }

    /**
     * @return array
     */
    public function getClasses(): array
    {
        return $this->classes;
    }

    public function getAllStepDefinitions(): array
    {
        $this->parseAllClassesForSteps();
        return $this->availableStepsList;
    }

    private function parseAllClassesForSteps(): void
    {
        foreach ($this->classes as $class) {
            $this->parseAllClassMethodsForSteps(new \ReflectionClass($class));
        }
    }

    private function parseAllClassMethodsForSteps(
        \ReflectionClass $reflection_class
    ): void {
        $class_methods = $reflection_class->getMethods();
        /** @var \ReflectionMethod $class_method */
        foreach ($class_methods as $class_method) {
            $matches = $this->parseStringForStepDefinition(
                $class_method->getDocComment()
            );
            $this->addStepDefinitionMatchesToStepList($matches);
        }
    }

    private function parseStringForStepDefinition($string): array
    {
        $step_pattern_matches = [];
        $match_count = preg_match_all(
            self::DOCBLOCK_STEP_PATTERN,
            $string,
            $step_pattern_matches
        );
        if ($match_count) {
            return $step_pattern_matches[1];
        }
        return [];
    }

    private function addStepDefinitionMatchesToStepList(array $matches): void
    {
        foreach ($matches as $match) {
            if (!in_array($match, $this->availableStepsList, true)) {
                $this->availableStepsList[] = $match;
            }
        }
    }

}
