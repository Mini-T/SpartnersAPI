<?php

namespace App\Validators;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class NameValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$this->validateName($value)) {
            $this->context->buildViolation($constraint->message)->setParameter('{{ string }}', $value)->addViolation();
        }
    }

    public function validateName($nom) {
        $pattern = "/^[\p{L}\-' ]+$/u";

        if (preg_match($pattern, $nom)) {
            return true;
        } else {
            return false;
        }
    }

}