<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Service;

use App\Entity\EmbeddedToken;

/**
 * Class TokenGenerator
 * @package App\Service
 */
class TokenGenerator
{
    /**
     * TokenGenerator constructor.
     * @param \DateInterval $interval
     */
    public function __construct(\DateInterval $interval)
    {
        $this->interval = $interval;
    }

    /**
     * @return EmbeddedToken
     * @throws \Exception
     */
    public function generate(): EmbeddedToken
    {
        return EmbeddedToken::create($this->interval);
    }

}