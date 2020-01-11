<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace App\UseCase\Work\Member\Filter;

use App\Model\Work\Member\Status;

/**
 * Class Filter
 * @package App\UseCase\Work\Member\Filter
 */
class Filter
{
    public $name;
    public $email;
    public $group;
    public $status = Status::STATUS_ACTIVE;
}