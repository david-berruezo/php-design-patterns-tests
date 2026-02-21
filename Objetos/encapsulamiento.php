<?php
class Person
{
    public $name;
    protected $age;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function getAge()
    {
        return $this->age;
    }

    public function setAge(int $age)
    {
        $this->age = $age;
    }
}

# persona
$person = new Person("John");
echo "nombre: $person->name";
$person->name = "Maria";
echo "nombre: $person->name";
$person->setAge(45);
echo "age: " . $person->getAge();
