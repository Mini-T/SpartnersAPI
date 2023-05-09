<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\RefreshTokenRepository;
use Doctrine\ORM\Mapping as ORM;
use Gesdinet\JWTRefreshTokenBundle\Doctrine\RefreshTokenRepositoryInterface;
use Gesdinet\JWTRefreshTokenBundle\Entity\RefreshToken as BaseRefreshToken;
use UnexpectedValueException;

#[ORM\Entity(repositoryClass: RefreshTokenRepository::class)]
class RefreshToken extends BaseRefreshToken
{
}
