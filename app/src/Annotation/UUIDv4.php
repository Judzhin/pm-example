<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace App\Annotation;

/**
 * Class UUIDv4
 * @package App\Annotation
 */
class UUIDv4
{
    /** @const PATTERN */
    public const PATTERN = '[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89abd][0-9a-f]{3}-[0-9a-f]{12}';
}