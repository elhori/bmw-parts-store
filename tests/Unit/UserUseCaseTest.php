<?php

use App\Application\UseCases\UserUseCase;
use App\Domain\Contract\IUserRepository;
use App\Domain\Entities\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use PHPUnit\Framework\TestCase;

class UserUseCaseTest extends TestCase
{
    public function testGetAllReturnsUsers()
    {
        $userRepositoryMock = Mockery::mock(IUserRepository::class);

        $userRepositoryMock->shouldReceive('getAll')
            ->once()
            ->andReturn([new User(1, 'John Doe', 'john@example.com', 'hashedpassword')]);

        $useCase = new UserUseCase($userRepositoryMock);

        $users = $useCase->getAll();
        $this->assertCount(1, $users);
        $this->assertInstanceOf(User::class, $users[0]);
    }

    public function testGetPaginatedReturnsPaginatedUsers()
    {
        $userRepositoryMock = Mockery::mock(IUserRepository::class);

        $paginationMock = Mockery::mock(LengthAwarePaginator::class);
        $paginationMock->shouldReceive('count')->andReturn(5);
        $paginationMock->shouldReceive('items')->andReturn([new User(1, 'John Doe', 'john@example.com', 'hashedpassword')]);

        $userRepositoryMock->shouldReceive('getPaginated')
            ->once()
            ->andReturn($paginationMock);

        $useCase = new UserUseCase($userRepositoryMock);

        $pagination = $useCase->getPaginated(10);
        $this->assertInstanceOf(LengthAwarePaginator::class, $pagination);
        $this->assertCount(1, $pagination->items());
    }

    public function testGetByIdReturnsUser()
    {
        $userRepositoryMock = Mockery::mock(IUserRepository::class);

        $userRepositoryMock->shouldReceive('getById')
            ->once()
            ->with(1)
            ->andReturn(new User(1, 'John Doe', 'john@example.com', 'hashedpassword'));

        $useCase = new UserUseCase($userRepositoryMock);

        $user = $useCase->getById(1);
        $this->assertInstanceOf(User::class, $user);
    }

    public function testGetByIdReturnsNullIfUserNotFound()
    {
        $userRepositoryMock = Mockery::mock(IUserRepository::class);

        $userRepositoryMock->shouldReceive('getById')
            ->once()
            ->with(999)
            ->andReturn(null);

        $useCase = new UserUseCase($userRepositoryMock);

        $user = $useCase->getById(999);
        $this->assertNull($user);
    }

    public function testSearchReturnsUsers()
    {
        $userRepositoryMock = Mockery::mock(IUserRepository::class);

        $paginationMock = Mockery::mock(LengthAwarePaginator::class);
        $paginationMock->shouldReceive('count')->andReturn(1);
        $paginationMock->shouldReceive('items')->andReturn([new User(1, 'John Doe', 'john@example.com', 'hashedpassword')]);

        $userRepositoryMock->shouldReceive('search')
            ->once()
            ->with('John')
            ->andReturn($paginationMock);

        $useCase = new UserUseCase($userRepositoryMock);

        $pagination = $useCase->search('John');
        $this->assertInstanceOf(LengthAwarePaginator::class, $pagination);
        $this->assertCount(1, $pagination->items());
    }

    public function testCreateReturnsCreatedUser()
    {
        Hash::shouldReceive('make')
            ->once()
            ->with('admin2020')
            ->andReturn('hashed_password');

        $userRepositoryMock = Mockery::mock(IUserRepository::class);

        $userRepositoryMock->shouldReceive('create')
            ->once()
            ->andReturn(new User(null, 'John Doe', 'john@example.com', 'hashed_password'));

        $useCase = new UserUseCase($userRepositoryMock);

        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'admin2020'
        ];

        $user = $useCase->create($userData);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('John Doe', $user->name);
        $this->assertEquals('john@example.com', $user->email);
    }

    public function testUpdateReturnsUpdatedUser()
    {
        $userRepositoryMock = Mockery::mock(IUserRepository::class);

        $userRepositoryMock->shouldReceive('update')
            ->once()
            ->andReturn(new User(1, 'John Updated', 'johnupdated@example.com', 'hashedpassword'));

        $useCase = new UserUseCase($userRepositoryMock);

        $userData = [
            'name' => 'John Updated',
            'email' => 'johnupdated@example.com',
        ];

        $user = $useCase->update(1, $userData);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('John Updated', $user->name);
        $this->assertEquals('johnupdated@example.com', $user->email);
    }

    public function testDeleteReturnsTrueWhenSuccessful()
    {
        $userRepositoryMock = Mockery::mock(IUserRepository::class);

        $userRepositoryMock->shouldReceive('delete')
            ->once()
            ->with(1)
            ->andReturn(true);

        $useCase = new UserUseCase($userRepositoryMock);

        $result = $useCase->delete(1);
        $this->assertTrue($result);
    }

    public function testDeleteReturnsFalseWhenFailed()
    {
        $userRepositoryMock = Mockery::mock(IUserRepository::class);

        $userRepositoryMock->shouldReceive('delete')
            ->once()
            ->with(999)
            ->andReturn(false);

        $useCase = new UserUseCase($userRepositoryMock);

        $result = $useCase->delete(999);
        $this->assertFalse($result);
    }

    public function testChangePasswordReturnsTrueWhenSuccessful()
    {
        $userRepositoryMock = Mockery::mock(IUserRepository::class);

        $userRepositoryMock->shouldReceive('changePassword')
            ->once()
            ->with(1, 'newpassword')
            ->andReturn(true);

        $useCase = new UserUseCase($userRepositoryMock);

        $result = $useCase->changePassword(1, 'newpassword');
        $this->assertTrue($result);
    }

    public function testChangePasswordReturnsFalseWhenFailed()
    {
        $userRepositoryMock = Mockery::mock(IUserRepository::class);

        $userRepositoryMock->shouldReceive('changePassword')
            ->once()
            ->with(999, 'newpassword')
            ->andReturn(false);

        $useCase = new UserUseCase($userRepositoryMock);

        $result = $useCase->changePassword(999, 'newpassword');
        $this->assertFalse($result);
    }

    public function testChangeRoleToAdminReturnsTrueWhenSuccessful()
    {
        $userRepositoryMock = Mockery::mock(IUserRepository::class);

        $userRepositoryMock->shouldReceive('changeRoleToAdmin')
            ->once()
            ->with(1)
            ->andReturn(true);

        $useCase = new UserUseCase($userRepositoryMock);

        $result = $useCase->changeRoleToAdmin(1);
        $this->assertTrue($result);
    }

    public function testChangeRoleToManagerReturnsTrueWhenSuccessful()
    {
        $userRepositoryMock = Mockery::mock(IUserRepository::class);

        $userRepositoryMock->shouldReceive('changeRoleToManager')
            ->once()
            ->with(1)
            ->andReturn(true);

        $useCase = new UserUseCase($userRepositoryMock);

        $result = $useCase->changeRoleToManager(1);
        $this->assertTrue($result);
    }
}
