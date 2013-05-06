<?php
namespace Xi\Fixtures;

use Doctrine\ORM\Query;

class PersistingTest extends TestCase
{
    /**
     * @test
     */
    public function automaticPersistCanBeTurnedOn()
    {
        $this->factory->define('SpaceShip')
            ->field('name', 'Zeta');
        
        $this->factory->persistOnGet();
        $ss = $this->factory->get('SpaceShip');
        $this->em->flush();
        
        $this->assertNotNull($ss->getId());
        $this->assertEquals($ss, $this->em->find('Xi\Fixtures\TestEntity\SpaceShip', $ss->getId()));
    }
    
    /**
     * @test
     */
    public function doesNotPersistByDefault()
    {
        $this->factory->define('SpaceShip')
            ->field('name', 'Zeta');

        $ss = $this->factory->get('SpaceShip');
        $this->em->flush();
        
        $this->assertNull($ss->getId());
        $this->assertEmpty($this->getAllSpaceShipsQuery()->getResult());
    }
    
    /**
     * @test
     */
    public function isAbleToGetUnpersistedEntityEvenIfPersistingIsOn()
    {
        $this->factory->define('SpaceShip')
            ->field('name', 'Normandy');

        $this->factory->persistOnGet();
        $ss = $this->factory->getUnpersisted('SpaceShip');
        $this->em->flush();
        
        $this->assertNull($ss->getId());
        $this->assertEmpty($this->getAllSpaceShipsQuery()->getResult());
    }
    
    /**
     * @return Query
     */
    private function getAllSpaceShipsQuery()
    {
        return $this->em
            ->createQueryBuilder()
            ->select('ss')
            ->from('Xi\Fixtures\TestEntity\SpaceShip', 'ss')
            ->getQuery();
    }
    
}
