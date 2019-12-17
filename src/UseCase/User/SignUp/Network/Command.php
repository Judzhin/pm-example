<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\User\SignUp\Network;

/**
 * Class Command
 * @package App\UseCase\User\SignUp\Network
 */
class Command
{
    /** @var string */
    public $network;

    /** @var string */
    public $identity;

    /** @var string */
    public $firstName;

    /** @var string */
    public $lastName;

    /**
     * Command constructor.
     * @param string $network
     * @param string $identity
     */
    public function __construct(string $network, string $identity)
    {
        $this->network = $network;
        $this->identity = $identity;
    }
}