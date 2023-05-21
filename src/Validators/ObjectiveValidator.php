<?php

namespace App\Validators;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ObjectiveValidator extends ConstraintValidator {
    private $validValues = ['Perte de poids', 'Prise de masse musculaire', 'Renforcement musculaire', 'Améliorer son endurance', 'Sèche'];
    public function validate($value, Constraint $constraint) {
        if (!in_array($value, $this->validValues)) {
            $this->context->buildViolation($constraint->message)->setParameter('{{ string }}', $value)->addViolation();
        };
    }
}
