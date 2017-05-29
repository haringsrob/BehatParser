<?php

namespace BehatParserTest\Fixtures;

class FeatureContextWithDoubleStepAnnotation
{

    /**
     * @When I have a step definition
     * @When I have another step definition
     */
    public function exampleStepWithDoubleAnnotation(): void
    {
    }

}
