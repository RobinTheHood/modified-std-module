<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;
use RobinTheHood\ModifiedStdModule\Classes\CaseConverter;

final class CaseConverterTest extends TestCase
{
    public function testCanConvertScreamingToCamel(): void
    {
        $camelCase = CaseConverter::screamingToCamel('MODULE_MC_MY_FIRST_MODULE');
        $this->assertEquals('moduleMcMyFirstModule', $camelCase);
        $this->assertNotEquals('ModuleMcMyFirstModule', $camelCase);
    }

    public function testCanConvertScreamingToLisp(): void
    {
        $lispCase = CaseConverter::screamingToLisp('MODULE_MC_MY_FIRST_MODULE');
        $this->assertEquals('module-mc-my-first-module', $lispCase);
    }
}
