<?php


namespace App\Repository\User;


use App\Repository\Base\BaseRepository;

/**
 * Class UserRepository
 * @package App\Repository\User
 * @author Josef Banya
 */
class UserRepository extends BaseRepository
{
    const ROLES = [
       1 => 'ADMIN',
    ];

    /** @var string  */
    protected $tableName = 'user';
}