<?php

namespace Xi\FixtureFactory;

use PHPUnit_Framework_TestCase;
use PHPUnit_Framework_Error;
use Xi\FixtureFactory\TestDb;
use Doctrine\ORM\EntityManager;
use Exception;

abstract class TestCase extends PHPUnit_Framework_TestCase
{
    /**
     * @var TestDb
     */
    protected $testDb;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * Public to allow access from the broken 5.3 closures.
     *
     * @var FixtureFactory
     */
    public $factory;

    public function setUp()
    {
        parent::setUp();

        $here = dirname(__FILE__);

        $this->testDb = new TestDb(
            $here . '/TestEntity',
            $here . '/TestProxy',
            'Xi\FixtureFactory\TestProxy'
        );

        $this->em = $this->testDb->createEntityManager();

        $this->factory = new FixtureFactory($this->em);
        $this->factory->setEntityNamespace('Xi\FixtureFactory\TestEntity');
    }
    
    /**
     * @return Exception
     */
    protected function assertThrows($func, $exceptionType = '\Exception')
    {
        try {
            $func();
        } catch (Exception $e) {
        }
        if (!isset($e)) {
            $this->fail("Expected $exceptionType but nothing was thrown");
        }
        if ($e instanceof PHPUnit_Framework_Error) {
            $this->fail('Expected exception but got a PHP error: ' . $e->getMessage());
        }
        if (!($e instanceof $exceptionType)) {
            $this->fail("Excpected $exceptionType but " . get_class($e) . " was thrown");
        }
        return $e;
    }
}
