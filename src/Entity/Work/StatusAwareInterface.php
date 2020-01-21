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
     * @return Status
     */
    public function getStatus();

    /**
     * @param StatusInterface $status
     * @return $this
     */
    public function setStatus(StatusInterface $status): self;

    /**
     * @return bool
     */
    public function isActive(): bool;

    /**
     */
    public function isArchived(): bool;
}