<?php

namespace App\Models;

use CodeIgniter\Model;

class BigDataModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'bigdata_products';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'ean',
        'name',
        'price',
        'brand',
        'producer',
        'grammage',
        'feature',
        'category',
        'height',
        'width'
    ];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function get_products_by_limit($id, $search, $limit, $offset, $order, $by)
    {
        $builder = $this->db->table('bigdata_products');
        if ($order) $builder->orderBy($by, $order);
        if ($search) {
            $builder->like('name', $search);
            $builder->orLike('brand', $search);
            $builder->orLike('ean', $search);
        }

        if ($limit) {
            $query = $builder->get($limit, $offset);
            if ($query->getNumRows() > 0) return $query->getResultArray();
            else return array();
        } else {
            return $builder->countAllResults();
        }
    }

    public function get_product_by_id($id)
    {
        $builder = $this->db->table('bigdata_products');
        $builder->orderBy('id', 'ASC');
        $builder->where('id', $id);
        $query = $builder->get();
        if ($query->getNumRows() > 0) {
            return $query->getResultArray();
        } else {
            return array();
        }
    }

    public function delete_product($id)
    {
        $builder = $this->db->table('bigdata_products');
        $builder->where('id', $id);
        $builder->delete();
    }

    public function update_product($data)
    {
        $builder = $this->db->table('bigdata_products');
        $builder->set($data);
        $builder->where('id', $data['id']);
        $builder->update();
    }
}
