<?php

namespace Tests\Unit\Repositories;

use App\Models\Account;
use App\Repositories\AccountRepository;
use Tests\TestCase;
use Mockery;

class AccountRepositoryTest extends TestCase
{
    private $accountMock;
    private $accountRepositoryMock;
    
    protected function setUp(): void
    {
        parent::setUp();

        $this->accountMock = Mockery::mock(Account::class);
        $this->accountRepositoryMock = Mockery::mock(
            AccountRepository::class, 
            [$this->accountMock]
        );
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
    
    public function testGetAll()
    {
        // Create mock Accounts
        $this->accountRepositoryMock
            ->shouldReceive('getAll')
            ->andReturn([
                new Account(),
                new Account(),
                new Account(),
            ]);
    
        $accounts = $this->accountRepositoryMock->getAll();
    
        $this->assertCount(3, $accounts);
    }

    public function testGetById()
    {
        $this->accountRepositoryMock
            ->shouldReceive('getAll')
            ->andReturn(new Account());
    
        $account = $this->accountRepositoryMock->getAll(Account::factory()->create()->id);

        $this->assertInstanceOf(Account::class, $account);
    }
    
    public function testUpdate()
    {
        $this->accountRepositoryMock
            ->shouldReceive('update')->andReturnUsing(
                fn ($id, $email, $password) => tap(new Account(), function ($account) use ($email, $password) {
                    $account->email = $email;
                    $account->password = $password;
                })
            );
    
        $updatedAccount = $this->accountRepositoryMock->update('9b9ff231-e8b4-42d1-a1f4-51eb384002ee', 'newemail@example.com', 'newpassword');
    
        $this->assertEquals('newemail@example.com', $updatedAccount->email);
    }
    
    public function testCreate()
    {
        $this->accountRepositoryMock
            ->shouldReceive('create')
            ->andReturnUsing(
                fn ($email, $password) => tap(new Account(), function ($account) use ($email, $password) {
                    $account->email = $email;
                    $account->password = $password;
                })
            );
    
        $createdAccount = $this->accountRepositoryMock->create('newAccount@example.com', 'password');
    
        $this->assertInstanceOf(Account::class, $createdAccount);
    }

    public function testDelete()
    {
        $account = Account::factory()->create();

        $this->accountRepositoryMock
            ->shouldReceive('delete')
            ->with($account->id)
            ->andReturn(true);

        $result = $this->accountRepositoryMock->delete($account->id);

        $this->assertTrue($result);
    }
}
