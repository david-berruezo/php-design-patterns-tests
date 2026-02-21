<?php
class Project
{
    protected string $name;
    protected array $team = [];

    public function __construct(string $name , array $team = [])
    {
        $this->name = $name;
        $this->team = $team;
    }

    public static function init(string $name , array $team = [])
    {
        return new static($name , $team);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTeam(): array
    {
        return $this->team;
    }

    public function addMember(User $user)
    {
        $this->team[] = $user;
    }
}


class User
{
    protected $name;
    protected $email;

    public function __construct($name , $email)
    {
        $this->name  = $name;
        $this->email = $email;
    }
}

# creamos una instancia de proyecto
$project = Project::init(
    "Project 1",
    array(
        new User("Dolores","doloresbernad@gmail.com"),
        new User("David","davidberruezo@davidberruezo.com"),
    )
);
$antonio = new User("Antonio","antonioberruezo@gmail.com");
# añadimos un miembro
$project->addMember($antonio);

# printeamos el objeto
var_dump($project);


# Hacemos una prueba para hacer un casting de Array a un Objeto en concreto
# primero añadimos registros de usuarios a nuestro vector
$vector_usuarios = array();
$vector_objetos_usuarios = array();
$registro_usurio = array(
    "name" => "Dolores",
    "email" => "doloresbernad@gmail.com",
);
array_push($vector_usuarios,$registro_usuario);
$registro_usurio = array(
    "name" => "Antonio",
    "email" => "toniberruezo@gmail.com",
);
array_push($vector_usuarios,$registro_usuario);
$registro_usurio = array(
    "name" => "David",
    "email" => "davidberruezo@davidberruezo.com",
);
array_push($vector_usuarios,$registro_usuario);

foreach ($vector_usuarios as $vector_usuario) {
    $objeto = (User) $vector_usuario;
    array_push($vector_objetos_usuarios,$objeto);
}

print_r($vector_objetos_usuarios);