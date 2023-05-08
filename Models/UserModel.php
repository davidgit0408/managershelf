<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    function __construct()
    {
        $this->tableName = 'users_fb';
        $this->primaryKey = 'id';
    }

    /*
     * Insert / Update facebook profile data into the database
     * @param array the data for inserting into the table
     */
    public function checkUser($userData = array())
    {
        if (!empty($userData)) {
            //check whether user data already exists in database with same oauth info
            $this->db->select($this->primaryKey);
            $this->db->from($this->tableName);
            $this->db->where(array('oauth_provider' => $userData['oauth_provider'], 'oauth_uid' => $userData['oauth_uid']));
            $prevQuery = $this->db->get();
            $prevCheck = $prevQuery->getNumRows();

            if ($prevCheck > 0) {
                $prevResult = $prevQuery->getRowArray();

                //update user data
                $userData['modified'] = date("Y-m-d H:i:s");
                $update = $this->db->update($this->tableName, $userData, array('id' => $prevResult['id']));

                //get user ID
                $userID = $prevResult['id'];
            } else {
                //insert user data
                $userData['created']  = date("Y-m-d H:i:s");
                $userData['modified'] = date("Y-m-d H:i:s");
                $insert = $this->db->insert($this->tableName, $userData);

                //get user ID
                $userID = $this->db->insert_id();
            }
        }

        //return user ID
        return $userID ? $userID : FALSE;
    }


    public function insert_user($data)
    {
        $this->db->insert('users', $data);
    }

    public function get_user_by_fb($id)
    {
        $builder = $this->db->table('users');
        $builder->orderBy('id', 'ASC');
        $builder->getWhere('pass', $id);
        $query = $builder->get();

        if ($query->getNumRows() > 0) return $query->getRowArray();
        else return 0;
    }
}
