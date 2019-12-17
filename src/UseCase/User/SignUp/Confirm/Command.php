<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\User\SignUp\Confirm;

/**
 * Class Command
 * @package App\UseCase\User\SignUp\Confirm
 */
class Command
{
    /** @var string */
    public $token;

    /**
     * Command constructor.
     * @param string $token
     */
    public function __construct(string $token)
    {
        $this->token = $token;
    }
}