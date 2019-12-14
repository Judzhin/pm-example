<?php
/**
 * @access protected
 * @author Judzhin Miles <info[woof-woof]msbios.com>
 */
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Webmozart\Assert\Assert;

/**
 * Class Name
 * @package App\Entity
 * @ORM\Embeddable
 */
class Name
{
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $first;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $last;

    /**
     * Name constructor.
     *
     * @param string $first
     * @param string $last
     */
    public function __construct(string $first, string $last)
    {
        Assert::notEmpty($first);
        Assert::notEmpty($last);

        $this->first = $first;
        $this->last = $last;
    }

    /**
     * @return string
     */
    public function getFirst(): string
    {
        return $this->first;
    }

    /**
     * @param string $first
     * @return Name
     */
    public function setFirst(string $first): self
    {
        $this->first = $first;
        return $this;
    }

    /**
     * @return string
     */
    public function getLast(): string
    {
        return $this->last;
    }

    /**
     * @param string $last
     * @return Name
     */
    public function setLast(string $last): self
    {
        $this->last = $last;
        return $this;
    }

    /**
     * @return string
     */
    public function getDisplay():string
    {
        return implode(' ', [
            $this->getFirst(),
            $this->getLast()
        ]);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->getDisplay();
    }


}
