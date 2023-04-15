<?php

namespace App\Models;

use CodeIgniter\Model;

class GroupsModel extends Model
{
    protected $table            = 'auth_groups';
    protected $returnType       = 'object';
    protected $useSoftDeletes   = true;
    protected $useTimestamps    = true;
    protected $allowedFields    = [
        'name',
        'description',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public function insertPermissions($data)
    {
        $query = $this->db->table('auth_groups_permissions')->insertBatch($data);
        return true;
    }

    public function deletePermissions($groupID)
    {
        $query = $this->db->table('auth_groups_permissions')->delete(['group_id' => $groupID]);
        return true;
    }

}
