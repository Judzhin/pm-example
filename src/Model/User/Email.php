<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Model\User;

use App\Exception\InvalidArgumentException;
use Webmozart\Assert\Assert;

/**
 * Class Email
 * @package App\Model\User
 */
class Email
{
    /** @var string */
    private $value;

    /**
     * Email constructor.
     * @param string $value
     * @throws \Throwable
     */
    public function __construct(string $value)
    {
        Assert::notEmpty($value);
        
        if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
            throw InvalidArgumentException::incorrectEmail();
        }

        $this->value = mb_strtolower($value);
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getValue();
    }

}