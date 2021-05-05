<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Secretsanta_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_participants()
    {
        $query = $this->db->get('secretSanta');
        return $query->result();
    }

    public function add_participant($data)
    {
        return $this->db->insert('secretSanta', $data);
    }

    public function find_participant($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('secretSanta');
        return $query->row();
    }

    public function update_participant($id,$data)
    {
        $this->db->where('id',$id);
        return $this->db->update('secretSanta', $data);

    }

    public function delete_participant($id)
    {
        return $this->db->delete('secretSanta',['id' => $id]);
    }
}
