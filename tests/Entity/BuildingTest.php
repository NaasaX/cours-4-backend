<?php

namespace App\Tests\Entity;

use App\Entity\Building;
use App\Entity\Person;
use PHPUnit\Framework\TestCase;

class BuildingTest extends TestCase
{
    public function testBuildingCreation(): void
    {
        $building = new Building();
        $building->setName('Test Building');
        $building->setAddress('123 Test Street');
        $building->setFloors(10);

        $this->assertEquals('Test Building', $building->getName());
        $this->assertEquals('123 Test Street', $building->getAddress());
        $this->assertEquals(10, $building->getFloors());
        $this->assertCount(0, $building->getPeople());
    }

    public function testAddPerson(): void
    {
        $building = new Building();
        $building->setName('Test Building');

        $person = new Person();
        $person->setFirstName('John');
        $person->setLastName('Doe');

        $building->addPerson($person);

        $this->assertCount(1, $building->getPeople());
        $this->assertTrue($building->getPeople()->contains($person));
        $this->assertEquals($building, $person->getBuilding());
    }

    public function testRemovePerson(): void
    {
        $building = new Building();
        $building->setName('Test Building');

        $person = new Person();
        $person->setFirstName('John');
        $person->setLastName('Doe');

        $building->addPerson($person);
        $this->assertCount(1, $building->getPeople());

        $building->removePerson($person);
        $this->assertCount(0, $building->getPeople());
        $this->assertNull($person->getBuilding());
    }
}
