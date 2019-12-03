<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\SignUp\Network;

/**
 * Class Command
 * @package App\UseCase\SignUp\Network
 */
class Command
{
    /** @var string */
    public $network;

    /** @var string */
    public $identity;

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