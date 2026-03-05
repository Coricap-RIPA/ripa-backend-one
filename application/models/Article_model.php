<?php

class Article_model extends CI_Model
{
    function __construct()
    {
        parent::__construct();
    }
    

    function get_article($id_article)
    {
        return $this->db->get_where('article',array('id_article'=>$id_article))->row_array();
    }

    function get_daily_article($daily_date)
    {
        return $this->db->get_where('article',array('date_enregistrement'=>$daily_date))->result_array();
    }

    function get_amout_daily_article($daily_date)
    {
        $query = $this->db->query("SELECT SUM(prix_article) AS montant FROM article WHERE date_com_enregistrement ='$daily_date'")->row_array();
        return $query['montant'];
    }

    function get_amout_achat_daily_article($daily_date)
    {
        $query = $this->db->query("SELECT SUM(prix_achat) AS montant FROM article WHERE date_com_enregistrement ='$daily_date'")->row_array();
        return $query['montant'];
    }



    function get_quantite_total_article()
    {
        $query = $this->db->query("SELECT SUM(quantite) AS quantite_total FROM article")->row_array();
        return $query['quantite_total'];
    }

    function get_prix_achat_total_article()
    {
        $query = $this->db->query("SELECT SUM(prix_achat) AS prix_achat_total FROM article")->row_array();
        return $query['prix_achat_total'];
    }

    function get_prix_vente_total_article()
    {
        $query = $this->db->query("SELECT SUM(prix_vente) AS prix_vente_total FROM article")->row_array();
        return $query['prix_vente_total'];
    }


    function get_quantite_total_article_by_date($start_date,$end_date)
    {
       
        $query = $this->db->query("SELECT SUM(quantite) AS quantite_total FROM article WHERE date_enregistrement_article BETWEEN '$start_date' AND '$end_date'")->row_array();
        return $query['quantite_total'];
    }

    function get_prix_achat_total_article_by_date($start_date,$end_date)
    {
        $query = $this->db->query("SELECT SUM(prix_achat) AS prix_achat_total FROM article WHERE date_enregistrement_article BETWEEN '$start_date' AND '$end_date'")->row_array();
        return $query['prix_achat_total'];
    }

    function get_prix_vente_total_article_by_date($start_date,$end_date)
    {
        $query = $this->db->query("SELECT SUM(prix_vente) AS prix_vente_total FROM article WHERE date_enregistrement_article BETWEEN '$start_date' AND '$end_date'")->row_array();
        return $query['prix_vente_total'];
    }

    

    function get_article_by_date($start_date,$end_date)
    {
        $query = $this->db->query("SELECT * FROM article WHERE date_enregistrement_article BETWEEN '$start_date' AND '$end_date'")->result_array();
        return $query;
    }

    
    function get_all_article()
    {
        $this->db->order_by('id_article', 'desc');
        return $this->db->get('article')->result_array();
    }

    function get_all_articles_sections_by_id_section_artilce($id_section_article)
    {
        $this->db->order_by('id_article_section', 'desc');
        $this->db->where('id_section_article',$id_section_article);
        return $this->db->get('article_section')->result_array();
    }


    function get_all_articles_by_id_foreign_categorie($id_foreign_categorie)
    {
        $this->db->order_by('id_article', 'desc');
        $this->db->where('id_foreign_categorie',$id_foreign_categorie);
        return $this->db->get('article')->result_array();
    }


    function get_all_article_id_name_filed()
    {
        $query = $this->db->query("SELECT id_article,nom_article FROM article ")->result_array();
        return $query;
    }
        
   
    function add_article($params)
    {
        $this->db->insert('article',$params);
        return $this->db->insert_id();
    }
    
  
    function update_article($id_article,$params)
    {
        $this->db->where('id_article',$id_article);
        return $this->db->update('article',$params);
    }
    

    function delete_article($id_article)
    {
        $this->db->where("id_article", $id_article);
        return $this->db->delete("type_article");
    }

    function get_article_by_id_categorie($id_categorie){
        $this->db->order_by('id_article', 'desc');
        return $this->db->get_where('article',array('id_foreign_categorie'=>$id_categorie))->result_array();
    }

    function get_one_article_by_id_categorie($id_categorie){
        $this->db->order_by('id_article', 'desc');
        $this->db->limit(1);
        return $this->db->get_where('article',array('id_foreign_categorie'=>$id_categorie))->row_array();
    }


    function get_article_by_id_categorie_id_sous_categorie($id_categorie,$id_sous_categorie){
        $this->db->order_by('id_article', 'desc');
        return $this->db->get_where('article',array('id_foreign_categorie'=>$id_categorie,'id_foreign_sous_categorie'=>$id_sous_categorie))->result_array();
    }


    function get_article_by_id_categorie_for_home($id_categorie){
        $this->db->order_by('id_article', 'asc');
        $this->db->limit(4);
        return $this->db->get_where('article',array('id_foreign_categorie'=>$id_categorie,'id_foreign_is_acceuille'=>1))->result_array();
    }

    function get_four_last_article(){
        $this->db->order_by('id_article', 'desc');
        $this->db->limit(4);
        return $this->db->get('article')->result_array();
    }

    function get_six_last_article(){
        $this->db->order_by('id_article', 'desc');
        $this->db->limit(6);
        return $this->db->get('article')->result_array();
    }

    function get_nine_last_article(){
        $this->db->order_by('id_article', 'desc');
        $this->db->limit(9);
        return $this->db->get('article')->result_array();
    }

    function get_article_by_id_fournisseur_id_categorie($id_fournisseur, $id_categorie){
        $this->db->order_by('id_article', 'asc');
        return $this->db->get_where('article',array('id_foreign_fournisseur'=>$id_fournisseur,'id_foreign_categorie'=>$id_categorie))->result_array();
    }

    function get_article_by_id_fournisseur_id_categorie_id_sous_categorie($id_fournisseur, $id_categorie,$id_sous_categorie){
        $this->db->order_by('id_article', 'desc');
        return $this->db->get_where('article',array('id_foreign_fournisseur'=>$id_fournisseur,'id_foreign_categorie'=>$id_categorie,'id_foreign_sous_categorie'=>$id_sous_categorie))->result_array();
    }

    function get_fournisseur_id_categorie_article($id_fournisseur, $id_categorie){
        $this->db->order_by('id_article', 'desc');
        return $this->db->get_where('article',array('id_foreign_fournisseur'=>$id_fournisseur,'id_foreign_categorie'=>$id_categorie))->result_array();
    }


    function get_fournisseur_article($id_fournisseur){
        $this->db->order_by('id_article', 'desc');
        return $this->db->get_where('article',array('id_foreign_fournisseur'=>$id_fournisseur) )->result_array();
    }


    function get_article_by_sql($sql)
    {
        $query = $this->db->query("SELECT * FROM article WHERE ".$sql." ORDER BY id_article  DESC ")->result_array();
        return $query;
    }


    function get_article_by_sql_by_limit($sql,$limit)
    {
        //echoDie("SELECT * FROM article WHERE ".$sql." ORDER BY id_article  DESC LIMIT $limit");
        $query = $this->db->query("SELECT * FROM article WHERE ".$sql." ORDER BY id_article  DESC LIMIT $limit")->result_array();
        return $query;
    }


    function get_four_article_inner_section_by_id_section_article($id_section_article)
    {
        $query = $this->db->query("SELECT * FROM article_section INNER JOIN article ON article_section.id_article=article.id_article WHERE article_section.id_section_article=$id_section_article ORDER BY article.id_article DESC LIMIT 4")->result_array();
        return $query;
    }

    function get_six_article_inner_section_by_id_section_article($id_section_article)
    {
        $query = $this->db->query("SELECT * FROM article_section INNER JOIN article ON article_section.id_article=article.id_article WHERE article_section.id_section_article=$id_section_article ORDER BY article.id_article DESC LIMIT 6")->result_array();
        return $query;
    }


    function get_sum_article_by_id_foreign_categorie($id_foreign_categorie)
    {
        $query = $this->db->query("SELECT COUNT(id_foreign_categorie) FROM article WHERE id_foreign_categorie=$id_foreign_categorie")->row_array();
        return $query['COUNT(id_foreign_categorie)'];
    }


    function get_last_article_by_limit($int_limit){
        $this->db->order_by('id_article', 'desc');
        $this->db->limit($int_limit);
        return $this->db->get('article')->result_array();
    }

    function get_article_by_limit($int_limit)
    {
        $query = $this->db->query("SELECT * FROM article  ORDER BY article.id_article DESC LIMIT $int_limit")->result_array();
        return $query;
    }


    function get_article_section_by_limit($int_limit,$id_section_article)
    {
        $query = $this->db->query("SELECT * FROM article_section INNER JOIN article ON article_section.id_article=article.id_article WHERE article_section.id_section_article=$id_section_article ORDER BY article_section.id_article_section DESC LIMIT $int_limit")->result_array();
        return $query;
    }



    function get_all_articles_by_limit_offset($limit,$offset)
    {
        $query  = $this->db->query("SELECT * FROM article ORDER BY id_article DESC LIMIT $limit OFFSET $offset")->result_array();
        return $query;
    }


    function get_all_articles_by_sql_limit_offset($sql,$limit,$offset)
    {
        $query  = $this->db->query("SELECT * FROM article WHERE ".$sql." ORDER BY id_article DESC LIMIT $limit OFFSET $offset")->result_array();
        return $query;
    }


    function get_all_articles_section_by_limit_offset_id_section_article($limit,$offset,$id_section_article)
    {
        $query  = $this->db->query("SELECT * FROM article_section INNER JOIN article ON article_section.id_article=article.id_article WHERE article_section.id_section_article=$id_section_article ORDER BY article_section.id_article_section DESC LIMIT  $limit OFFSET $offset")->result_array();
        return $query;
    }


    function get_all_articles_by_limit_offset_id_foreign_categorie($limit,$offset,$id_foreign_categorie)
    {
        $query  = $this->db->query("SELECT * FROM article INNER JOIN categorie ON article.id_foreign_categorie=categorie.id_categorie WHERE article.id_foreign_categorie=$id_foreign_categorie ORDER BY article.id_article DESC LIMIT  $limit OFFSET $offset")->result_array();
        return $query;
    }


    function get_article_by_limit_id_foreign_categorie($int_limit,$id_foreign_categorie)
    {
        $query = $this->db->query("SELECT * FROM article WHERE id_foreign_categorie=$id_foreign_categorie  ORDER BY article.id_article DESC LIMIT $int_limit")->result_array();
        return $query;
    }


    function get_article_by_id_categorie_limit_exclude_artilce($id_categorie, $id_article ,$limit){
        $query  = $this->db->query("SELECT * FROM article WHERE  article.id_foreign_categorie=$id_categorie AND article.id_article<>$id_article ORDER BY id_article DESC LIMIT $limit")->result_array();
        return $query;
    }
    
}