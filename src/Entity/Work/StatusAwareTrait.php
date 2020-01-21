<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Entity\Work;

use App\Model\Work\StatusInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Trait StatusAwareTrait
 * @package App\Entity\Work
 */
trait StatusAwareTrait
{
    /**
     * @var StatusInterface
     * @ORM\Column(type="work_status", length=16)
     */
    private $status;

    /**
     * @return StatusInterface
     */
    public function getStatus(): StatusInterface
    {
        return $this->status;
    }

    /**
     * @param StatusInterface $status
     * @return $this
     */
    public function setStatus(StatusInterface $status): self
    {
        $this->status = $status;
        return $this;
    }

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status->isActive();
    }

    /**
    */
    public function isArchived(): bool
    {
        return $this->status->isArchived();
    }
}