<?php

class Job_request_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    

    function get_job_request($id_job_request)
    {
        return $this->db->get_where('job_request',array('id_job_request'=>$id_job_request))->row_array();
    }

    function get_avalaible_job_request()
    {
        return $this->db->get_where('job_request',array('status_job_request'=>1))->result_array();
    }

        
    function get_all_job_request()
    {
        $this->db->order_by('id_job_request', 'desc');
        return $this->db->get('job_request')->result_array();
    }
        
   
    function add_job_request($params)
    {
        $this->db->insert('job_request',$params);
        return $this->db->insert_id();
    }
    
  
    function update_job_request($id_job_request,$params)
    {
        $this->db->where('id_job_request',$id_job_request);
        return $this->db->update('job_request',$params);
    }
    

    function delete_job_request($id_job_request)
    {
        $this->db->where("id_job_request", $id_job_request);
        return $this->db->delete("job_request");
    }
}