<?php

namespace App\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CheckComment extends Constraint
{
    public $message = 'Le message doit contenir au max 240 caractères';
}