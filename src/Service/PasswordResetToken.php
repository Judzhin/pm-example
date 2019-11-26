<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Service;

use App\Entity\EmbeddedToken;

/**
 * Class PasswordResetToken
 * @package App\Service
 */
class PasswordResetToken
{
    private $interval = "P3D";

    /**
     * ResetTokenizer constructor.
     * @param $interval
     */
    public function __construct($interval)
    {
        $this->interval = $interval;
    }

    /**
     * @return EmbeddedToken
     * @throws \Exception
     */
    public function generate(): EmbeddedToken
    {
        return EmbeddedToken::create(new \DateInterval($this->interval));
    }

}