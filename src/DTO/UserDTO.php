<?php

namespace App\DTO;

class UserDTO
{
    public string $firstname;
    public string $lastname;
    public string $email;
    public string $sex;
    public string $city;
    public string $level;
    public string $objective;
    public ?string $description;

    public function __construct(string $firstname, string $lastname, string $email, string $sex, string $city, ?string $description, string $level, string $objective)
    {
        $this->firstname = $firstname;
        $this->lastname = $lastname;
        $this->email = $email;
        $this->sex = $sex;
        $this->city = $city;
        $this->level = $level;
        $this->objective = $objective;
        $this->description = $description;
    }
}