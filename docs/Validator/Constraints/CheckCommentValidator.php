<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @Annotation
 */
final class CheckCommentValidator extends ConstraintValidator
{
    

    public function __construct(){
       
    }

    public function validate($value, Constraint $constraint): void
    {
        //if ($this->us->checkPassword($value) != 0) {
            $this->context->buildViolation($constraint->message)->addViolation();
        //}
    }
}