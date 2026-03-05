<?php

    class Section_article_model extends CI_Model{

        function add($params){
            $this->db->insert("section_article", $params);
            return $this->db->insert_id();
        }

        function update($id, $params){
            $this->db->where("id_section_article", $id);
            return $this->db->update("section_article", $params);
        }

        function get_all_section_articles(){
            $this->db->order_by('id_section_article', 'asc');
            return $this->db->get('section_article')->result_array();
        }

        
        function get_section_article($id){
            return $this->db->get_where('section_article',array('id_section_article'=>$id))->row_array();
        }

        function get_fromatter_section_articles(){
            $query = $this->db->query("SELECT * FROM `section_article` WHERE `id_section_article`<>3 AND `id_section_article` <> 9 AND `id_section_article` <>10")->result_array();
            return $query;
        }

        function get_article_section_article_by_id_article($id_article){
            $query = $this->db->query("SELECT * FROM `article_section` WHERE `id_article`=$id_article ")->row_array();
            return $query;
        }

        function delete_section_article($token){
            $this->db->where("id_section_article", $token);
            return $this->db->delete("section_article");
        }

    }

?>