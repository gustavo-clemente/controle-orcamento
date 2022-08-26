<?php

namespace App\Test\Unit\Facade;

use App\Entity\AbstractContaContabil;
use App\Facade\AbstractContaContabilFacade;
use App\Repository\AbstractContaContabilRepository;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use App\Exception\DuplicateEntityException;
use App\Exception\EntityNotFoundException;

class ContaContabilFacadeTest extends TestCase
{
    private MockObject|AbstractContaContabilRepository $repository;
    private MockObject|EntityManager $entityManager;
    private MockObject|AbstractContaContabilFacade $facade;

    public function setUp(): void
    {
        $this->repository = $this->getMockBuilder(AbstractContaContabilRepository::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->entityManager = $this->createMock(EntityManager::class);
    }

    public function testCreateThrowException()
    {
        $this->expectException(DuplicateEntityException::class);
        $contaContabil = $this->getMockForAbstractClass(AbstractContaContabil::class);

        $this->repository->method('findMonthDescription')->willReturn($contaContabil);
        $this->facade = $this->getMockForAbstractClass(AbstractContaContabilFacade::class, [$this->repository, $this->entityManager]);

        $this->facade->create($contaContabil);

    }

    public function testUpdateThrowException()
    {
        $this->expectException(DuplicateEntityException::class);
        $contaContabil = $this->getMockForAbstractClass(AbstractContaContabil::class);

        $this->repository->method('findMonthDescription')->willReturn($contaContabil);
        $this->facade = $this->getMockForAbstractClass(AbstractContaContabilFacade::class, [$this->repository, $this->entityManager]);

        $this->facade->update($contaContabil);

    }

    public function testGetEntityFromIdThrowException()
    {
        $this->expectException(EntityNotFoundException::class);

        $this->repository->method('find')->willReturn(null);
        $this->facade = $this->getMockForAbstractClass(AbstractContaContabilFacade::class, [$this->repository, $this->entityManager]);

        $this->facade->getEntityFromId(1);
    }

    /**
     * @dataProvider duplicateTestProvider
     */
    public function testIsDuplicateReturn($findMonthDescriptionReturn, $expectedReturn)
    {
        $contaContabil = $this->getMockForAbstractClass(AbstractContaContabil::class);

        $this->repository->method('findMonthDescription')->willReturn($findMonthDescriptionReturn);
        $this->facade = $this->getMockForAbstractClass(AbstractContaContabilFacade::class, [$this->repository, $this->entityManager]);

        self::assertSame($expectedReturn, $this->facade->isDuplicate($contaContabil));
    }

    public function duplicateTestProvider()
    {

        $contaContabil = $this->getMockForAbstractClass(AbstractContaContabil::class);
        return [

            'teste com duplicata' => [$contaContabil, true],
            'teste sem duplicata' => [null, false]
        ];
    }
}
