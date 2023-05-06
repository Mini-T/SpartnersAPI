<?php

namespace App\Validators;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class SexValidator extends ConstraintValidator {
    private $validValues = ['Homme', 'Femme', 'Non-binaire'];
    public function validate($value, Constraint $constraint) {
        if (!in_array($value, $this->validValues)) {
            $this->context->buildViolation($constraint->message)->setParameter('{{ string }}', $value)->addViolation();
        };
    }
}
