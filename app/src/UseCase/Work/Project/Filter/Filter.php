<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
namespace App\UseCase\Work\Project\Filter;

use App\Model\Work\Status;

/**
 * Class Filter
 * @package App\UseCase\Work\Project\Filter
 */
class Filter
{
    public $name;
    public $status = Status::STATUS_ACTIVE;
}