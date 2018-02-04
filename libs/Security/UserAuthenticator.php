<?php


namespace App\Security\Authenticator;

use App\Repository\User\UserRepository;
use Nette\Object;
use Nette\Security\AuthenticationException;
use Nette\Security\IAuthenticator;
use Nette\Security\Identity;
use Nette\Security\Passwords;

/**
 * Class UserAuthenticator
 * @package App\Security\Authenticator
 * @author  Josef Banya
 */
class UserAuthenticator extends Object implements IAuthenticator
{
    /** @var UserRepository @inject */
    protected $userRepository;

    /**
     * Authenticator Constructor
     * @param \App\Repository\User\UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->setUserRepository($userRepository);
    }

    /**
     * @param array $credentials
     * @return \Nette\Security\Identity
     * @throws \Nette\Security\AuthenticationException
     */
    public function authenticate(array $credentials)
    {
        list($email, $password) = $credentials;

        $user = $this->getUserRepository()->getItem(['email' => $email]);

        if (!$user) {
            throw new AuthenticationException('Uživatel nenalezen', self::IDENTITY_NOT_FOUND);
        }

        if (!Passwords::verify($password, $user->password)) {
            throw new AuthenticationException('Špatné heslo nebo email', self::INVALID_CREDENTIAL);
        }

        $userData = [
            'email' => $user->email,
            'role' => $user->role
        ];

        return new Identity($user->id, [$user->role], $userData);
    }

    /**
     * @return UserRepository
     */
    public function getUserRepository()
    {
        return $this->userRepository;
    }

    /**
     * @param UserRepository $userRepository
     * @return self Provides Fluent Interface
     */
    public function setUserRepository($userRepository)
    {
        $this->userRepository = $userRepository;
        return $this;
    }





}