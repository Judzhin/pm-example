<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\User\PasswordReset\Confirm;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Command
 * @package App\UseCase\User\PasswordReset\Confirm
 */
class Command
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $token;

    /**
     * @var string
     * @Assert\NotBlank()
     * @Assert\Length(min=6)
     */
    public $plainPassword;

    /**
     * Command constructor.
     * @param string $token
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }

}