<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\User\Email\Confirm;

use App\Model\User\Email;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Command
 * @package App\UseCase\User\Email\Confirm
 */
class Command
{
    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $id;

    /**
     * @var string
     * @Assert\NotBlank()
     */
    public $token;

    /**
     * Command constructor.
     * @param string $id
     * @param string $token
     */
    public function __construct(string $id, string $token)
    {
        $this->id = $id;
        $this->token = $token;
    }
}