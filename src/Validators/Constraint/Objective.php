<?php
namespace App\Validators\Constraint;

use App\Validators\ObjectiveValidator;
use App\Validators\SexValidator;
use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
class Objective extends Constraint {
    public $message = "la chaine '{{ string }}' n'est pas un objectif valide";

    public function validatedBy(): string
    {
        return ObjectiveValidator::class;
    }
}