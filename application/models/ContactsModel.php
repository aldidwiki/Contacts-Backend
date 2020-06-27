<?php

class ContactsModel extends CI_Model
{
    public function getContacts()
    {
        $this->db->order_by('nama', 'ASC');
        return  $this->db->get('telepon')->result_array();
    }

    public function deleteContacts($id)
    {
        $this->db->delete('telepon', ['id' => $id]);
        return $this->db->affected_rows();
    }

    public function insertContacts($data)
    {
        $this->db->insert('telepon', $data);
        return $this->db->affected_rows();
    }

    public function updateContacts($data, $id)
    {
        $this->db->update('telepon', $data, ['id' => $id]);
        return $this->db->affected_rows();
    }

    public function searchContacts($keywords)
    {
        $this->db->like('nama', $keywords);
        $this->db->order_by('nama', 'ASC');
        return  $this->db->get('telepon')->result_array();
    }
}
