<?php

namespace App\Models;

use CodeIgniter\Model;

class PermissionsModel extends Model
{
    protected $table            = 'auth_permissions';
    protected $returnType       = 'object';
    protected $allowedFields    = [
        'name',
        'description'
    ];

    public function onGroups($groupId) {
        $query = $this->db->table('auth_groups_permissions')
                ->where('group_id', $groupId);
        return $query->get()->getResult();
    }

}
