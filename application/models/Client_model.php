<?php

class Client_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /* 
     * Get clients by id_client
     */
    function get_client($id_client)
    {
        return $this->db->get_where('client',array('id_client'=>$id_client))->row_array();
    }

    function get_client_by_tel($tel){
        return $this->db->get_where('client',array('tel_whatsapp_client'=>$tel))->row_array();
    }
        
    /*
     * Get all client
     */
    function get_all_clients()
    {
        $this->db->order_by('id_client', 'desc');
        return $this->db->get('client')->result_array();
    }
        
    /*
     * function to add new clients
     */
    function add_client($params)
    {
        $this->db->insert('client',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update clients
     */
    function update_client($id_client,$params)
    {
        $this->db->where('id_client',$id_client);
        return $this->db->update('client',$params);
    }
    
    /*
     * function to delete clients
     */
    function delete_client($token)
    {
        $this->db->where("id_client", $token);
        return $this->db->delete("client");
    }


    function delete_client_by_tel($tel)
    {
        $this->db->where("tel_whatsapp_client", $tel);
        return $this->db->delete("client");
    }
}