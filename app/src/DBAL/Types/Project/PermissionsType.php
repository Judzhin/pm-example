<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\DBAL\Types\Project;

use App\Model\User\Role;
use App\Model\Work\Project\Permission;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\JsonType;

/**
 * Class PermissionsType
 *
 * @package App\DBAL\Types\Project
 */
class PermissionsType extends JsonType
{
    /** @const NAME */
    public const NAME = 'project_permissions';

    /**
     * @inheritdoc
     *
     * @return string
     */
    public function getName(): string
    {
        return self::NAME;
    }

    /**
     * @inheritDoc
     *
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return string
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if ($value instanceof ArrayCollection) {
            $value = array_map([self::class, 'deserialize'], $value->toArray());
        }

        return parent::convertToDatabaseValue($value, $platform);
    }

    /**
     * @inheritdoc
     *
     * @param $value
     * @param AbstractPlatform $platform
     * @return array|ArrayCollection|null
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (!is_array($value = parent::convertToPHPValue($value, $platform))) {
            return $value;
        }

        return new ArrayCollection(array_filter(array_map([self::class, 'serialize'], $value)));
    }

    /**
     * @param Permission $permission
     * @return string
     */
    private static function deserialize(Permission $permission): string
    {
        return $permission->getValue();
    }

    /**
     * @param string $value
     * @return Permission|null
     */
    private static function serialize(string $value): ?Permission
    {
        return in_array($value, Permission::values(), true) ? new Permission($value) : null;
    }

}