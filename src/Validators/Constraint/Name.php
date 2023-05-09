<?php

namespace App\Validators\Constraint;

use App\Validators\NameValidator;
use Attribute;
use Symfony\Component\Validator\Constraint;


#[Attribute]
class Name extends Constraint
{
    public $message = "le nom '{{ string }}' contient des caractères invalides.";

    public function validatedBy(): string
    {
        return NameValidator::class;
    }
}