<?php
namespace App\Tests\Service;

use App\Service\UserService;
use App\Dto\RegisterUserDto;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use PHPUnit\Framework\TestCase;
class UserServiceTest extends TestCase
{
    public function testRegisterUser(): void
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $passwordHasher = $this->createMock(UserPasswordHasherInterface::class);

        $passwordHasher
            ->expects($this->once())
            ->method('hashPassword')
            ->willReturn('hashed_password_123');

        $entityManager
            ->expects($this->once())
            ->method('persist');

        $entityManager
            ->expects($this->once())
            ->method('flush');

        $userService = new UserService($entityManager, $passwordHasher);

        $dto = new RegisterUserDto();
        $dto->name = 'Test';
        $dto->email = 'test@test.cz';
        $dto->password = 'heslo123';

        $result = $userService->registerUser($dto);

        $this->assertInstanceOf(User::class, $result);
        $this->assertEquals('Vojtěch', $result->getName());
        $this->assertEquals('vojta@test.cz', $result->getEmail());
        $this->assertEquals('hashed_password_123', $result->getPassword());
    }
}
