<?php
use PHPUnit\Framework\TestCase;
use App\Solve;

class SolveTest extends TestCase
{
    public function testInit()
    {
        $count = 1;

        $this->assertEquals(1, $count);
    }

    public function testReadFile()
    {
        // file is in root directory.
        $solve = new Solve();

        $this->assertEmpty($solve->getCities());

        $solve->readFile();

        $this->assertNotEmpty($solve->getCities());
    }

    public function testCalculateDistances()
    {
        $solve = new Solve();
        $solve->readFile();

        $this->assertEmpty($solve->getDistances());

        $solve->calculateDistances();

        $this->assertNotEmpty($solve->getDistances());
    }



}
