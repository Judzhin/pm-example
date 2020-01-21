<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Model\Work\Member;

use App\Entity\Work\Member;
use Webmozart\Assert\Assert;

/**
 * Class Status
 * @package App\Model\Work\Member
 * @deprecated
 */
class Status extends \App\Model\Work\Status
{
//    /** @const STATUS_NONE */
//    public const STATUS_NONE = 'NONE';
//
//    /** @const STATUS_ARCHIVED */
//    public const STATUS_ARCHIVED = 'ARCHIVED';
//
//    /** @const STATUS_ACTIVE */
//    public const STATUS_ACTIVE = 'ACTIVE';
//
//    /** @var string */
//    private $value = self::STATUS_NONE;
//
//    /**
//     * Status constructor.
//     * @param string $value
//     */
//    public function __construct(string $value)
//    {
//        Assert::oneOf($value, [
//            self::STATUS_NONE,
//            self::STATUS_ACTIVE,
//            self::STATUS_ARCHIVED,
//        ]);
//
//        $this->value = $value;
//    }
//
//    /**
//     * @return string
//     */
//    public function getValue(): string
//    {
//        return $this->value;
//    }
//
//    /**
//     * @param Member $status
//     * @return bool
//     */
//    public function isEqual(Member $status): bool
//    {
//        return $this->value === $status->getName();
//    }
//
//    /**
//     * @return bool
//     */
//    public function isActive(): bool
//    {
//        return self::STATUS_ACTIVE === $this->value;
//    }
//
//    /**
//     * @return bool
//     */
//    public function isArchived():bool
//    {
//        return self::STATUS_ARCHIVED === $this->value;
//    }
//
//    /**
//     * @param string $name
//     * @return Status
//     */
//    private static function factory(string $name): self
//    {
//        return new self($name);
//    }
//
//    /**
//     * @return Status
//     */
//    public static function default(): self
//    {
//        return self::factory(self::STATUS_NONE);
//    }
//
//    /**
//     * @return Status
//     */
//    public static function active(): self
//    {
//        return self::factory(self::STATUS_ACTIVE);
//    }
//
//    /**
//     * @return Status
//     */
//    public static function archived(): self
//    {
//        return self::factory(self::STATUS_ARCHIVED);
//    }
//
//    /**
//     * @return string
//     */
//    public function __toString()
//    {
//        return $this->value;
//    }
}