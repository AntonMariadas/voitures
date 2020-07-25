<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;

class RechercheVoiture
{
    /**
     * @Assert\LessThanOrEqual(
     * propertyPath = "maxAnnee",
     * message = "Doit être plus petit ou égal que l'année max"
     * )
     */
    private $minAnnee;

    /**
     * @Assert\GreaterThanOrEqual(
     * propertyPath = "minAnnee",
     * message = "Doit être plus grand ou égal que l'année min"
     * )
     */
    private $maxAnnee;

    public function getMinAnnee()
    {
        return $this->minAnnee;
    }

    public function getMaxAnnee()
    {
        return $this->maxAnnee;
    }

    public function setMinAnnee(int $minAnnee)
    {
        $this->minAnnee = $minAnnee;
        return $this->minAnnee;
    }

    public function setMaxAnnee(int $maxAnnee)
    {
        $this->maxAnnee = $maxAnnee;
        return $this->maxAnnee;
    }

}