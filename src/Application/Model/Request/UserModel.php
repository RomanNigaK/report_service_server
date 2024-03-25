<?php

namespace App\Application\Model\Request;

use App\Application\Ex\RequiredField;
use Symfony\Component\HttpFoundation\Request;


class UserModel
{
    private string $name;
    private string $sername;
    private string $patronymic;
    private string $email;
    private string $phone;
    private string $password;
    private string $role;

    public function __construct()
    {
    }

    public static function create(Request $request): self
    {

        $data = $request->toArray();
        
        $user = new self($request);
        
        $user->name = $data["name"] ?? throw new RequiredField("name");
        
        $user->sername = $data["sername"] ?? throw new RequiredField("sername");
        
        $user->patronymic = $data["patronymic"] ?? throw new RequiredField("patronymic");
        
        $user->email = $data["email"] ?? throw new RequiredField("email");
        
        $user->phone = $data["phone"] ?? throw new RequiredField("phone");

        $user->password = $data["password"] ?? throw new RequiredField("password");

        return $user;
    }

    

    public static function addEmployee(Request $request): self
    {

        $data = $request->toArray();
        
        $user = new self($request);
        
        $user->name = $data["name"] ?? throw new RequiredField("name");
        
        $user->sername = $data["sername"] ?? throw new RequiredField("sername");
        
        $user->patronymic = $data["patronymic"] ?? throw new RequiredField("patronymic");
        
        $user->email = $data["email"] ?? throw new RequiredField("email");
        
        $user->phone = $data["phone"] ?? throw new RequiredField("phone");

        $user->password = $data["password"] ?? throw new RequiredField("password");

        $user->role = $data["role"] ?? throw new RequiredField("role");

        return $user;
    }


    public static function authorization(Request $request): self
    {
        $data = $request->toArray();
        $user = new self($request);
        $user->email = $data["email"] ?? throw new RequiredField("email");
        $user->password = $data["password"] ?? throw new RequiredField("password");

        return $user;
    }

    public function getName()
    {
        return $this->name;
    }
    public function getSername()
    {
        return $this->sername;
    }
    public function getPatronymic()
    {
        return $this->patronymic;
    }
    public function getEmail()
    {
        return $this->email;
    }
    public function getPhone()
    {
        return $this->phone;
    }
    public function getPassword(): string
    {
        return $this->password;
    }
    public function getRole(): string
    {
        return $this->role;
    }
}
