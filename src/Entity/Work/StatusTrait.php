<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Entity\Work;

use App\Model\Work\Status;
use App\Model\Work\StatusInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Trait StatusTrait
 * @package App\Entity\Work
 */
trait StatusTrait
{
    /**
     * @var Status
     * @ORM\Column(type="work_status", length=16)
     */
    private $status;

    /**
     * @return Status
     */
    public function getStatus(): StatusInterface
    {
        return $this->status;
    }

    /**
     * @param Status $status
     * @return $this
     */
    public function setStatus(Status $status): self
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