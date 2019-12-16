<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace App\UseCase\User\Filter;

/**
 * Class Command
 * @package App\UseCase\User\Filter
 */
class Command
{
    /** @var string */
    public $name;

    /** @var string */
    public $email;

    /** @var string */
    public $status;

    /** @var array */
    public $roles;
}