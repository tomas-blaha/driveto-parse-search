<?php

require('Base.php');

class CarTest extends Base {

    private $car;

    public function setUp() {
        parent::setUp();

        $this->car = $this->container->getService('car');
    }

    public function testSearch() {
        Tester\Environment::lock();
        
        $this->clearDatabase();
        $this->insertMockData();
        
        Tester\Assert::same(1, count($this->car->search(4)));
        Tester\Assert::same(1, count($this->car->search(4, 1.2)));
        
        Tester\Assert::same(0, count($this->car->search(6)));
        Tester\Assert::same(0, count($this->car->search(4, 2)));
    }

}

//(new CarTest)->run();
