<?php

namespace App\Models;

use CodeIgniter\Model;

class GroupPermissionsModel extends Model
{
    protected $table            = 'auth_groups_permissions';
    protected $returnType       = 'object';
    protected $allowedFields    = [
        'group_id',
        'permission_id'
    ];

}
