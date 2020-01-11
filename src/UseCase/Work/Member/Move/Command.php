<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */

namespace App\UseCase\Work\Member\Move;

use App\Entity\Work\Member;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Command
 * @package App\UseCase\Work\Member\Move
 */
class Command
{
    /**
     * @var UuidInterface
     * @Assert\NotBlank()
     */
    public $id;

    ///**
    // * @var string
    // * @Assert\NotBlank()
    // */
    //public $group;

    /**
     * Command constructor.
     * @param UuidInterface $id
     */
    public function __construct(UuidInterface $id)
    {
        $this->id = $id;
    }

    /**
     * @param Member $member
     * @return Command
     */
    public static function parse(Member $member): self
    {
        return new self($member->getId());
    }
}