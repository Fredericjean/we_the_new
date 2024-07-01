<?php

namespace App\Tests\Entity;

use App\Entity\User;
use App\Tests\Traits\TestTrait;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\ORMDatabaseTool;

class UserEntityTest extends KernelTestCase
{
    use TestTrait;

    protected ?ORMDatabaseTool $databaseTool = null;

    public function setUp(): void
    {
        parent::setUp();

        $this->databaseTool = self::getContainer()->get(DatabaseToolCollection::class)->get();
    }

    public function testRepositoryCount(): void
    {
        
        $this->databaseTool->loadAliceFixture([
            \dirname(__DIR__) . '/Fixtures/UserFixtures.yaml'
        ]);

        $userRepo = self::getContainer()->get(UserRepository::class);

        $users = $userRepo->findAll();

        $this->assertCount(11, $users);
    }

    private function getEntity(): User
    {
        return (new User)
            ->setEmail('test@test.com')
            ->setPassword('test')
            ->setLastName('test')
            ->setFirstName('test');
    }

    public function testValidEntity(): void
    {
        $this->assertHasErrors($this->getEntity());
    }

    /**
     * @dataProvider provideEmail
     *
     * @param string $email
     * @param integer $number
     * @return void
     */
    public function testInvalidEmail(string $email, int $number): void
    {
        $user = $this->getEntity()
            ->setEmail($email);

        $this->assertHasErrors($user, $number);
    }

    public function testFindPaginateOrderByDate(): void
    {
        $repo = self::getContainer()->get(UserRepository::class);
        $users = $repo->findPaginateOrderByDate(9, 1);

        $this->assertCount(9, $users);
    }

    public function testFindPaginateorderByDateWithSearch(): void
    {
        $repo = self::getContainer()->get(UserRepository::class);

        $users = $repo->findPaginateOrderByDate(9, 1, 'admin');

        $this->assertCount(1, $users);
    }

    public function testFindPaginateOrderByDateWithInvalidArgument(): void
    {
        $repo = self::getContainer()->get(UserRepository::class);

        $this->expectException(\TypeError::class);

        $repo->findPaginateOrderByDate('test', 0, 'invalid');
    }

    public function provideEmail(): array
    {
        return [
            'non_unique' => [
                'email' => 'admin@test.com',
                'number' => 1,
            ],
            'max_length' => [
                'email' => str_repeat('a', 180) . '@test.com',
                'number' => 1,
            ],
            'empty' => [
                'email' => '',
                'number' => 1,
            ],
            'invalid' => [
                'email' => "test.com",
                'number' => 1,
            ],
        ];
    }

    /**
     * @dataProvider provideFirstName
     *
     * @param string $firstName
     * @param integer $number
     * @return void
     */
    public function testInvalidFirstName(string $firstName, int $number): void
    {
        $user = $this->getEntity()
            ->setFirstName($firstName);
        $this->assertHasErrors($user, $number);
    }

    public function provideFirstName(): array
    {
        return [
            'max_length' => [
                str_repeat('a', 256),
                'number' => 1,
            ],
            'empty' => [
                'firstName' => '',
                'number' => 1,
            ]
        ];
    }

    /**
     * @dataProvider provideLastName
     *
     * @param string $lastName
     * @param integer $number
     * @return void
     */
    public function testInvalidLastName(string $lastName, int $number): void
    {
        $user = $this->getEntity()
            ->setlastName($lastName);
        $this->assertHasErrors($user, $number);
    }

    public function provideLastName(): array
    {
        return [
            'max_length' => [
                str_repeat('a', 256),
                'number' => 1,
            ],
            'empty' => [
                'lastName' => '',
                'number' => 1,
            ],
        ];
    }

    /**
     * @dataProvider providePhone
     *
     * @param string $Phone
     * @param integer $number
     * @return void
     */
    public function testInvalidPhone(string $phone, int $number): void
    {
        $user = $this->getEntity()
            ->setPhone($phone);
        $this->assertHasErrors($user, $number);
    }

    public function providePhone(): array
    {
        return [
            'max_length' => [
                str_repeat('a', 11),
                'number' => 1,
            ],
            'min_length' => [
                str_repeat('a', 9),
                'number' => 1,
            ],

        ];
    }


    public function tearDown(): void
    {
        $this->databaseTool = null;
        parent::tearDown();
    }
}
