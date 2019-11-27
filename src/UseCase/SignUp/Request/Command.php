<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\SignUp\Request;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Command
 * @package App\UseCase\SignUp\Request
 */
class Command
{
    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    public $email;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=6)
     */
    public $password;
}