<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Model;

use Webmozart\Assert\Assert;

/**
 * Class Role
 * @package App\Model
 */
class Role
{
    private const USER = 'ROLE_USER';
    private const ADMIN = 'ROLE_ADMIN';

    /** @var string */
    private $value;

    /**
     * Role constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        Assert::oneOf($name, [
            self::USER,
            self::ADMIN
        ]);
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

    /**
     * @return Role
     */
    public static function user(): self
    {
        return new self(self::USER);
    }

    /**
     * @return Role
     */
    public static function admin(): self
    {
        return new self(self::ADMIN);
    }

    /**
     * @return bool
     */
    public function isUser(): bool
    {
        return self::USER === $this->value;
    }

    /**
     * @return bool
     */
    public function isAdmin(): bool
    {
        return self::ADMIN === $this->value;
    }

    /**
     * @param self $role
     * @return bool
     */
    public function isEqual(self $role): bool
    {
        return $this->getValue() === $role->getValue();
    }
}