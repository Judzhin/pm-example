<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Service;

/**
 * Class TokenGeneratorFactory
 * @package App\Service
 */
class TokenGeneratorFactory
{
    /**
     * @param string $interval
     * @return TokenGenerator
     * @throws \Exception
     */
    public function create(string $interval): TokenGenerator
    {
        return new TokenGenerator(
            new \DateInterval($interval)
        );
    }

}