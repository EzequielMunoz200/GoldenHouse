<?php


namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;


class PropertySearch
{
    /**
     * Undocumented variable
     *
     * @var int|null
     * 
     */
    private $maxPrice;

    /**
     * Undocumented variable
     *
     * @var int|null
     * @Assert\Range(min=10, max=400)
     */
    private $minSurface;

    /**
     * Get undocumented variable
     *
     * @return  int|null
     */ 
    public function getMaxPrice(): ?int
    {
        return $this->maxPrice;
    }

    /**
     * Set undocumented variable
     *
     * @param  int|null  $maxPrice  Undocumented variable
     *
     * @return  self
     */ 
    public function setMaxPrice(int $maxPrice): PropertySearch
    {
        $this->maxPrice = $maxPrice;

        return $this;
    }

    /**
     * Get undocumented variable
     *
     * @return  int|null
     */ 
    public function getMinSurface(): ?int
    {
        return $this->minSurface;
    }

    /**
     * Set undocumented variable
     *
     * @param  int|null  $minSurface  Undocumented variable
     *
     * @return  self
     */ 
    public function setMinSurface(int $minSurface): PropertySearch
    {
        $this->minSurface = $minSurface;

        return $this;
    }
}