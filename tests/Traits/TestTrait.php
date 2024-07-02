<?php

namespace App\Tests\Traits;

trait testTrait
{
    public function assertHasErrors(mixed $entity, int $number = 0): void
    {
        self::bootKernel();

        //On test l'entité avec validator
        $errors = self::getContainer()->get('validator')->validate($entity);

        //On instancie un tableau vide pour stocker les erreurs
        $messageErrors = [];
        //On parcours les erreurs et on les stocke dans le tableau
        foreach ($errors as $error) {
            $messageErrors[] = $error->getPropertyPath() . " => " . $error->getMessage();
        }


        $this->assertCount($number, $errors, implode(' ,',  $messageErrors));
    }
}
