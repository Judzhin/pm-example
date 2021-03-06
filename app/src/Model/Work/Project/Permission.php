<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\Model\Work\Project;

use Webmozart\Assert\Assert;

/**
 * Class Permission
 *
 * @package App\Model\Work\Project
 */
class Permission
{
    /** @const MANAGE_PROJECT_MEMBERS */
    public const MANAGE_PROJECT_MEMBERS = 'manage_project_members';
    public const VIEW_TASKS = 'view_tasks';
    public const MANAGE_TASKS = 'manage_tasks';

    /**
     * @var string
     */
    private $value;

    /**
     * Permission constructor.
     *
     * @param string $value
     */
    public function __construct(string $value)
    {
        Assert::oneOf($value, self::values());
        $this->value = $value;
    }

    /**
     * @return array
     */
    public static function values(): array
    {
        return [
            self::MANAGE_PROJECT_MEMBERS,
            self::VIEW_TASKS,
            self::MANAGE_TASKS
        ];
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param Permission $permission
     * @return bool
     */
    public function isValueEqual(Permission $permission): bool
    {
        return $this->getValue() === $permission->getValue();
    }

    /**
     * @param string $name
     * @return static
     */
    private static function factory(string $name): self
    {
        return new self($name);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->getValue();
    }
}