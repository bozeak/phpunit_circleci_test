<?php

namespace AppBundle\Factory;

use AppBundle\Entity\Dinosaur;
use AppBundle\Service\DinosaurLengthDeterninator;

class DinosaurFactory
{
    private $lengthDeterninator;

    public function __construct(DinosaurLengthDeterninator $lengthDeterninator)
    {
        $this->lengthDeterninator = $lengthDeterninator;
    }

    public function growVelociraptor(int $length): Dinosaur
    {
        return $this->createDinosaur('Velociraptor', true, $length);
    }

    public function growFromSpecification(string $specification): Dinosaur
    {
        $codeName = 'InG-'.random_int(1, 99999);
        $length = $this->lengthDeterninator->getLengthFromSpecification($specification);
        $isCarnivorous = false;

        if (stripos($specification, 'carnivorous') !== false) {
            $isCarnivorous = true;
        }

        $dinosaur = $this->createDinosaur($codeName, $isCarnivorous, $length);
        return $dinosaur;
    }

    private function createDinosaur(string $genus, bool $isCarnivorous, int $length): Dinosaur
    {
        $dinosaur = new Dinosaur($genus, $isCarnivorous);
        $dinosaur->setLength($length);
        return $dinosaur;
    }
}