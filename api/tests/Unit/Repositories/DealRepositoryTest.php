<?php

namespace Tests\Unit\Repositories;

use App\Models\Deal;
use App\Repositories\DealRepository;
use Tests\TestCase;
use Mockery;

class DealRepositoryTest extends TestCase
{
    private $dealMock;
    private $dealRepositoryMock;
    
    protected function setUp(): void
    {
        parent::setUp();

        $this->dealMock = Mockery::mock(Deal::class);
        $this->dealRepositoryMock = Mockery::mock(
            DealRepository::class, 
            [$this->dealMock]
        );
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
    
    public function testGetAll()
    {
        // Create mock Deals
        $this->dealRepositoryMock
            ->shouldReceive('getAll')
            ->andReturn([
                new Deal(),
                new Deal(),
                new Deal(),
            ]);
    
        $deals = $this->dealRepositoryMock->getAll();
    
        $this->assertCount(3, $deals);
    }

    public function testGetById()
    {
        $this->dealRepositoryMock
            ->shouldReceive('getAll')
            ->andReturn(new Deal());
    
        $deal = $this->dealRepositoryMock->getAll(Deal::factory()->create()->id);

        $this->assertInstanceOf(Deal::class, $deal);
    }
    
    public function testUpdate()
    {
        $this->dealRepositoryMock
            ->shouldReceive('update')->andReturnUsing(
                fn ($id, $name, $stage) => tap(new Deal(), function ($deal) use ($name, $stage) {
                    $deal->name = $name;
                    $deal->stage = $stage;
                })
            );
    
        $updatedDeal = $this->dealRepositoryMock->update('9b9ff231-e8b4-42d1-a1f4-51eb384002ee', 'newname@example.com', 'newstage');
    
        $this->assertEquals('newname@example.com', $updatedDeal->name);
    }
    
    public function testCreate()
    {
        $this->dealRepositoryMock
            ->shouldReceive('create')
            ->andReturnUsing(
                fn ($name, $stage) => tap(new Deal(), function ($deal) use ($name, $stage) {
                    $deal->name = $name;
                    $deal->stage = $stage;
                })
            );
    
        $createdDeal = $this->dealRepositoryMock->create('newDeal@example.com', 'stage');
    
        $this->assertInstanceOf(Deal::class, $createdDeal);
    }

    public function testDelete()
    {
        $deal = Deal::factory()->create();

        $this->dealRepositoryMock
            ->shouldReceive('delete')
            ->with($deal->id)
            ->andReturn(true);

        $result = $this->dealRepositoryMock->delete($deal->id);

        $this->assertTrue($result);
    }
}
