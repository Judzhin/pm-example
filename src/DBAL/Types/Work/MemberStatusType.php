<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\DBAL\Types\Work;

use App\Model\Work\Member\Status;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

/**
 * Class MemberStatusType
 * @package App\DBAL\Types\Work
 */
class MemberStatusType extends StringType
{
    /** @const NAME */
    public const NAME = 'work_member_status';

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
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return null|string
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): ?string
    {
        return $value instanceof Status ? $value->getName() : $value;
    }

    /**
     * @inheritdoc
     *
     * @param mixed $value
     * @param AbstractPlatform $platform
     * @return Status
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): Status
    {
        return new Status($value);
    }
}