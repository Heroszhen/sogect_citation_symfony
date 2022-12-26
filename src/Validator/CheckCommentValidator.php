<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class CheckCommentValidator extends ConstraintValidator
{
    private $parame;

    public function __construct(ParameterBagInterface $parame){
        $this->parame = $parame;
    }

    public function validate($value, Constraint $constraint)
    {
        /* @var App\Validator\CheckComment $constraint */

        if (strlen($value) <= (int)$this->parame->get("comment_message_length")) {
            return;
        }
/*
        // TODO: implement the validation here
        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();*/
        $this->context->buildViolation($constraint->message)->addViolation();
    }
}
