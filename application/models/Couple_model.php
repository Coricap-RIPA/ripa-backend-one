<?php

class Couple_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    
    /* 
     * Get couples by id_couple
     */
    function get_couple($id_couple)
    {
        return $this->db->get_where('couple',array('id_couple'=>$id_couple))->row_array();
    }

    function get_couple_by_tel($tel){
        return $this->db->get_where('couple',array('tel_whatsapp_couple'=>$tel))->row_array();
    }

    function get_couple_by_code($code){
        return $this->db->get_where('couple',array('code_couple'=>$code))->row_array();
    }

    function get_couple_by_code_by_date($code,$date_in){
        $query = $this->db->query("SELECT * FROM couple WHERE code_couple=$code AND date_mariage >='$date_in' ")->row_array();
        return $query;

    }
        
    /*
     * Get all couple
     */
    function get_all_couples()
    {
        $this->db->order_by('id_couple', 'desc');
        return $this->db->get('couple')->result_array();
    }
        
    /*
     * function to add new couples
     */
    function add_couple($params)
    {
        $this->db->insert('couple',$params);
        return $this->db->insert_id();
    }
    
    /*
     * function to update couples
     */
    function update_couple($id_couple,$params)
    {
        $this->db->where('id_couple',$id_couple);
        return $this->db->update('couple',$params);
    }
    
    /*
     * function to delete couples
     */
    function delete_couple($token)
    {
        $this->db->where("id_couple", $token);
        return $this->db->delete("couple");
    }

    function delete_couple_by_tel($tel)
    {
        $this->db->where("tel_whatsapp_couple", $tel);
        return $this->db->delete("couple");
    }


    function get_couple_article()  {
        $query = $this->db->query("SELECT * FROM couple_article ")->result_array();
        return $query;
    }


    function get_couple_article_by_id_foreign_couple($id_foreign_couple)  {
        $query = $this->db->query("SELECT * FROM couple_article WHERE id_foreign_couple=$id_foreign_couple ORDER BY id_couple_article DESC")->row_array();
        return $query;
    }

}