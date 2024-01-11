<?php

namespace App\Tests\Validator;

use App\Validator\Dni;
use App\Validator\DniValidator;
use Doctrine\DBAL\Exception\ConstraintViolationException;
use PHPUnit\Framework\TestCase;

class DniValidatorTest extends TestCase
{
    public function testSomething(): void
    {
        $validator = new DniValidator();
        $dni = new Dni();
        $validator->validate("12345678Z", $dni);


        $this->assertTrue(true);
    }

    public function testFailsIfOnlyCharacters(): void
    {
        $validator = new DniValidator();

        $this->expectException(ConstraintViolationException::class);
        $dni = new Dni();
        $validator->validate("12345688Z", $dni);

    }
}
