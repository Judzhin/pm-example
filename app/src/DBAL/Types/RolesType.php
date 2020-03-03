<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\DBAL\Types;

use App\Model\User\Role;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\JsonType;

/**
 * Class RolesType
 * @package App\DBAL\Types
 */
class RolesType extends JsonType
{
    /** @const NAME */
    public const NAME = 'roles';

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
     * @inheritdoc
     *
     * @param $value
     * @param AbstractPlatform $platform
     * @return string
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if (is_array($value)) {
            /**
             * @var int $key
             * @var Role|string $item
             */
            foreach ($value as $key => $item) {
                if ($item instanceof Role) {
                    $value[$key] = $item->getValue();
                }
            }
        }

        return parent::convertToDatabaseValue($value, $platform);
    }

    /**
     * @inheritdoc
     *
     * @param $value
     * @param AbstractPlatform $platform
     * @return array|null
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): ?array
    {
        /** @var array $value */
        $value = parent::convertToPHPValue($value, $platform);

        /**
         * @var int $key
         * @var string $name
         */
        foreach ($value as $key => $name) {
            $value[$key] = new Role($name);
        }

        return $value;
    }

}