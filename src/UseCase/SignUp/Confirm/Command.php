<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\SignUp\Confirm;

/**
 * Class Command
 * @package App\UseCase\SignUp\Confirm
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