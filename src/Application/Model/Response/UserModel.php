<?php

namespace App\Application\Model\Response;

use App\Entity\User;
use DateTime;

class UserModel
{

    public string $id;
    public DateTime $dateCreated;
    public string $name;
    public string $sername;
    public string $patronymic;
    public string $email;
    public string $phone;
    public ?string $role;


    public function __construct()
    {
    }

    public static function create(User $user, ?string $role = null): self
    {

        $response = new self($user);
        $response->name = $user->getName();
        $response->sername = $user->getSername();
        $response->patronymic = $user->getPatronymic();
        $response->email = $user->getEmail();
        $response->phone = $user->getPhone();
        $response->id = $user->getId();
        $response->dateCreated = $user->getDateCreated();

        if ($role) {
            $response->role = $role;
        }


        return $response;
    }
}
