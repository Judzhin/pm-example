<?php
/**
 * Created by PhpStorm.
 * User: judzhin
 * Date: 15.12.2019
 * Time: 00:03
 */

namespace App\Exception;

/**
 * Trait FactoryExceptionTrait
 * @package App\Exception
 */
trait FactoryExceptionTrait
{
    /**
     * @param string $message
     * @return FactoryExceptionTrait
     */
    private static function factory(string $message): self
    {
        return new self($message);
    }
}