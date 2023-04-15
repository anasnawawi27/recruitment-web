<?php

namespace App\Models;

use CodeIgniter\Model;

class AuthUsersPermissionsModel extends Model {

    protected $table         = 'auth_users_permissions';
    protected $returnType    = 'object';
    protected $useTimestamps = false;
    protected $allowedFields = [
        'user_id',
        'permission_id',
    ];

}
