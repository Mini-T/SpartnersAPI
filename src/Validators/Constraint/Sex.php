<?php
namespace App\Validators\Constraint;

use App\Validators\SexValidator;
use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class Sex extends Constraint {
    public $message = "la chaine '{{ string }}' n'est pas un sexe valide";

    public function validatedBy()
    {
        return SexValidator::class;
    }
}