<?php

class Training_request_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    

    function get_training_request($id_training_request)
    {
        return $this->db->get_where('training_request',array('id_training_request'=>$id_training_request))->row_array();
    }

    function get_avalaible_training_request()
    {
        return $this->db->get_where('training_request',array('status_training_request'=>1))->result_array();
    }

        
    function get_all_training_request()
    {
        $this->db->order_by('id_training_request', 'desc');
        return $this->db->get('training_request')->result_array();
    }
        
   
    function add_training_request($params)
    {
        $this->db->insert('training_request',$params);
        return $this->db->insert_id();
    }
    
  
    function update_training_request($id_training_request,$params)
    {
        $this->db->where('id_training_request',$id_training_request);
        return $this->db->update('training_request',$params);
    }
    

    function delete_training_request($id_training_request)
    {
        $this->db->where("id_training_request", $id_training_request);
        return $this->db->delete("training_request");
    }
}