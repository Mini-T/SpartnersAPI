<?php

namespace App\Validators;

use DateTime;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;


class BirthdateValidator extends ConstraintValidator
{
    public function validate(mixed $value, Constraint $constraint)
    {
        if (!$this->isAuthorized($value)) {
            $this->context->buildViolation($constraint->message)->setParameter('{{ string }}', $value)->addViolation();
        }
    }
    private function isAuthorized(String $birthDate): bool{

        $formattedBirthDate = DateTime::createFromFormat('Y-m-d', $birthDate);
        $now = new DateTime('now');
        if($now->diff($formattedBirthDate)->y < 16){
            return false;
        };
        return true;
    }
}