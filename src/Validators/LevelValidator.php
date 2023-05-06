<?php

namespace App\Validators;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class LevelValidator extends ConstraintValidator
{
    private $validValues = ['Débutant', 'Intermédiaire', 'Expert'];

    public function validate(mixed $value, Constraint $constraint)
    {
        if (!in_array($value, $this->validValues)) {
            $this->context->buildViolation($constraint->message)->setParameter('{{ string }}', $value)->addViolation();
        }
    }
}