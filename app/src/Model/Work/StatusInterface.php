<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Model\Work;

/**
 * Interface StatusInterface
 * @package App\Model\Work
 */
interface StatusInterface
{
    /**
     * @return string
     */
    public function getValue(): string;

    /**
     * @param StatusInterface $status
     * @return bool
     */
    public function isEqual(StatusInterface $status): bool;
}