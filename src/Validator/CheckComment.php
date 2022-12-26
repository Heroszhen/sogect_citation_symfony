<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class CheckComment extends Constraint
{
    /*
     * Any public properties become valid options for the annotation.
     * Then, use these in your validator class.
     */
    //public $message = 'Le message "{{ value }}" est trop long.';
    public $message = 'Le message est trop long.';
}
