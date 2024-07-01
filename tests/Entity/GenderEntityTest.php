<?php

namespace App\Tests\Entity;

use App\Entity\Product\Gender;
use App\Tests\Traits\testTrait;
use App\Repository\Product\GenderRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\ORMDatabaseTool;

class GenderEntityTest extends KernelTestCase

{
    use TestTrait;

    protected ?ORMDatabaseTool $databaseTool = null;

    public function setUp():void
     {
        parent::setUp();

        $this->databaseTool = self::getContainer()->get(DatabaseToolCollection::class)->get();
     }

     public function testRepositoryCount():void
     {
        $this->databaseTool->loadAliceFixture([
            \dirname(__Dir__) . '/Fixtures/GenderFixtures.yaml',
        ]);

        $genderRepo = self::getContainer()->get(GenderRepository::class);

        $genders = $genderRepo->findAll();

        $this->assertCount(1, $genders);
     }


     private function getEntity(): Gender

    {
        return (new Gender)
        ->setName('test');
    }

    public function testValidEntity():void
    {
        $this->assertHasErrors($this->getEntity());
    }

    /**
     * @dataProvider provideName
     *
     * @param string $name
     * @param integer $number
     * @return void
     */
    public function testInvalidName(string $name, int $number)
    {
        $gender= $this->getEntity()
        ->setName($name);

        $this->assertHasErrors($gender,$number);
    }

    public function provideName(): array
    {
        return[
            'max_length' =>[
                'name' =>str_repeat('a',256),
                'number'=> 1
            ],
            'empty' =>[
                'name' => '',
                'number' => 1,
            ]
            ];
    }

     public function tearDown():void
     {
        $this->databaseTool = null;
        parent::tearDown();
     }


}