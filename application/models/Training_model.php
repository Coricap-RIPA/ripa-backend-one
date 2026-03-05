<?php

class training_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    

    function get_training($id_training)
    {
        return $this->db->get_where('training',array('id_training'=>$id_training))->row_array();
    }

    function get_avalaible_training()
    {
        return $this->db->get_where('training',array('status_training'=>1))->result_array();
    }

        
    function get_all_training()
    {
        $this->db->order_by('id_training', 'desc');
        return $this->db->get('training')->result_array();
    }
        
   
    function add_training($params)
    {
        $this->db->insert('training',$params);
        return $this->db->insert_id();
    }
    
  
    function update_training($id_training,$params)
    {
        $this->db->where('id_training',$id_training);
        return $this->db->update('training',$params);
    }
    

    function delete_training($id_training)
    {
        $this->db->where("id_training", $id_training);
        return $this->db->delete("type_training");
    }
}