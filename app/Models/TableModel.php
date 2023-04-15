<?php

namespace App\Models;

use CodeIgniter\Model;

class TableModel extends Model
{

    protected $select = '*';
    protected $withUser         = false;
    protected $returnType       = 'object';
    protected $where            = [];
    protected $joins            = [];
    protected $filter           = [];
    protected $filterNull       = [];
    protected $filterAdditional = [];
    protected $order            = [];
    protected $group            = [];
    protected $limit            = [];

    function __construct($table, $softDelete = true)
    {
        $this->db = \Config\Database::connect();
        $this->builder = $this->setTable($table . ' a');
        $this->useSoftDeletes = $softDelete;
    }

    public function getAll()
    {
        $this->builder->select($this->select);
        $this->buildJoin();
        $this->buildWhere();
        $this->buildFilter();
        $this->buildGroup();
        $this->buildOrder();
        $this->buildLimit();
        return $this->builder->get()->getResult();
    }

    public function countAll()
    {
        $this->builder->select($this->select);
        $this->buildJoin();
        $this->buildWhere();
        $this->buildFilter();
        $this->buildGroup();
        return $this->builder->countAllResults();
    }

    public function buildOrder()
    {
        if ($this->order) {
            foreach ($this->order as $order) {
                $this->builder->orderBy($order['sort'], $order['order']);
            }
        }
    }

    public function buildWhere()
    {
        if ($this->where) {
            foreach ($this->where as $key => $value) {
                $this->builder->where($key, $value);
            }
        }
    }

    public function buildFilter()
    {
        if ($this->useSoftDeletes) {
            $this->builder->where('a.deleted_at', NULL);
        }
        if ($this->filter) {
            $i = 0;
            foreach ($this->filter as $col => $val) {
                if (!$val)
                    continue;
                if ($i == 0) {
                    $this->builder->groupStart();
                }
                $this->orLike($col, $val);
                $i++;
            }
            if ($i > 0) {
                $this->builder->groupEnd();
            }
        }
        if ($this->filterAdditional) {
            foreach ($this->filterAdditional as $filter) {
                if ($filter['function'] == 'groupStart') {
                    $this->builder->groupStart();
                } elseif ($filter['function'] == 'groupEnd') {
                    $this->builder->groupEnd();
                } else {
                    $this->builder->{$filter['function']}($filter['column'], $filter['value']);
                }
            }
        }
        if ($this->filterNull) {
            foreach ($this->filterNull as $column) {
                $this->builder->where($column . ' IS NULL');
            }
        }
    }

    public function buildJoin()
    {
        if ($this->joins) {
            foreach ($this->joins as $join) {
                $this->builder->join($join['table'], $join['on'], $join['type']);
            }
        }
        if ($this->withUser) {
            $this->builder->select('au1.name created_by, au2.name updated_by');
            $this->builder->join('auth_users au1', 'a.created_by = au1.id', 'left');
            $this->builder->join('auth_users au2', 'a.updated_by = au2.id', 'left');
        }
    }

    public function buildLimit()
    {
        if ($this->limit) {
            $this->builder->limit($this->limit['length'], $this->limit['start']);
        }
    }

    public function buildGroup()
    {
        if ($this->group) {
            $this->builder->groupBy($this->group);
        }
    }

    public function setSelect($select = '*')
    {
        $this->select = $select;
    }

    public function setOrder($order = [])
    {
        $this->order[] = $order;
    }

    public function setFilter($filter = [])
    {
        $this->filter = $filter;
    }

    public function setFilterAdditional($filter = [])
    {
        $this->filterAdditional = $filter;
    }

    public function setFilterNull($column = [])
    {
        $this->filterNull = $column;
    }

    public function setLimit($start = 0, $length = 10)
    {
        $this->limit['start'] = $start;
        $this->limit['length'] = $length;
    }

    public function setJoin($joins = [])
    {
        $this->joins = $joins;
    }

    public function setWhere($where = [])
    {
        $this->where = $where;
    }

    public function setGroup($group = [])
    {
        $this->group = $group;
    }

    public function withUser()
    {
        $this->withUser = true;
    }
}
