<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Entity\Work;

use App\Model\Work\Status;
use App\Model\Work\StatusInterface;

/**
 * Interface StatusAwareInterface
 * @package App\Entity\Work
 */
interface StatusAwareInterface
{
    /**
     * @return StatusInterface
     */
    public function getStatus(): StatusInterface;

    /**
     * @param StatusInterface $status
     * @return mixed
     */
    public function setStatus(StatusInterface $status);

    /**
     * @return bool
     */
    public function isActive(): bool;

    /**
     * @return bool
     */
    public function isArchived(): bool;
}