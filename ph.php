<?php

class Animal
{

    public $nom;
    public $age;
    public function __construct($nom, $age)
    {
        $this->nom = $nom;
        $this->age = $age;

    }

    public function parle()
    {
        return " Bonjour";
    }


}

class Chien extends Animal {
    public function parle() {

        return "hoow";
    }
}

class Chat extends Animal {
    public function parle() {

        return "myaw";
    }
}

$chien = new Chien("Rex", 3);
$chat = new Chat("Mimi", 2);

echo $chien->parle(); // Woof!
echo "<br>";
echo $chat->parle();  // Meow!