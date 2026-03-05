<?php

class Job_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    

    function get_job($id_job)
    {
        return $this->db->get_where('job',array('id_job'=>$id_job))->row_array();
    }

    function get_avalaible_job()
    {
        return $this->db->get_where('job',array('status_job'=>1))->result_array();
    }

        
    function get_all_job()
    {
        $this->db->order_by('id_job', 'desc');
        return $this->db->get('job')->result_array();
    }
        
   
    function add_job($params)
    {
        $this->db->insert('job',$params);
        return $this->db->insert_id();
    }
    
  
    function update_job($id_job,$params)
    {
        $this->db->where('id_job',$id_job);
        return $this->db->update('job',$params);
    }
    

    function delete_job($id_job)
    {
        $this->db->where("id_job", $id_job);
        return $this->db->delete("type_job");
    }
}