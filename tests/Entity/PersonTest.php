<?php

namespace App\Tests\Entity;

use App\Entity\Building;
use App\Entity\Person;
use PHPUnit\Framework\TestCase;

class PersonTest extends TestCase
{
    public function testPersonCreation(): void
    {
        $person = new Person();
        $person->setFirstName('John');
        $person->setLastName('Doe');
        $person->setEmail('john.doe@example.com');
        $person->setPhone('1234567890');

        $this->assertEquals('John', $person->getFirstName());
        $this->assertEquals('Doe', $person->getLastName());
        $this->assertEquals('john.doe@example.com', $person->getEmail());
        $this->assertEquals('1234567890', $person->getPhone());
    }

    public function testSetBuilding(): void
    {
        $person = new Person();
        $person->setFirstName('John');
        $person->setLastName('Doe');
        $person->setEmail('john.doe@example.com');

        $building = new Building();
        $building->setName('Test Building');
        $building->setAddress('123 Test Street');
        $building->setFloors(5);

        $person->setBuilding($building);

        $this->assertEquals($building, $person->getBuilding());
    }

    public function testPhoneIsOptional(): void
    {
        $person = new Person();
        $person->setFirstName('Jane');
        $person->setLastName('Smith');
        $person->setEmail('jane.smith@example.com');

        $this->assertNull($person->getPhone());

        $person->setPhone(null);
        $this->assertNull($person->getPhone());
    }
}
