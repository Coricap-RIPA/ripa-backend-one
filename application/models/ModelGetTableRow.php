<?php 





class ModelGetTableRow extends CI_Model {

    protected $table ='';

    /**
    *
    * @param array  $arrayWhereClause
    * @param string  $tableName
    */

    function getObjectFieldValue($arrayWhereClause, $tableName){
        $this->table = $tableName;
        $this->db->where($arrayWhereClause);
        return $this->db->get($this->table)->row();
    } 


    /**
    *
    * @param array  $arrayWhereClause
    * @param string  $tableName
    */

    function getObjectRow($arrayWhereClause, $tableName){
        $this->table = $tableName;
        $this->db->where($arrayWhereClause);
        return $this->db->get($this->table)->row();
    } 


    /**
    *
    * @param string  $tableName
    */

    function getAllRows($tableName){
        $this->table = $tableName;
        return $this->db->get($this->table)->result();
    } 

    /**
    *
    * @param string  $tableName
    * @param array  $arrayWhereClause
    */

    function getTotal ($tableName, $arrayWhereClause){
         $this->table = $tableName;
         $this->db->where($arrayWhereClause);
         return count( $this->db->get($this->table)->result() );
    } 


    /**
    *
    * @param string  $tableName
    * @param array  $arrayWhereClause
    */

    function getAllFilteredObject ($tableName, $arrayWhereClause){
        $this->table = $tableName;
        $this->db->where($arrayWhereClause);
        return  $this->db->get($this->table)->result();
   } 


           
    // Calcul des totaux Montant Payés
        function calulMontantTotalPayerPointEncodage($filter,$pointEncodage){
        $objectVec  = $this->getAllFilteredObject('trucvehicule',[$filter=>$pointEncodage]);
        $montant    = 0;
        if($objectVec){
            foreach ($objectVec as $key => $obj) {
                $factureObjet = $this->getObjectFieldValue(['intIdTruckVehicule'=>$obj->id,'intIdPointEncodage'=>$pointEncodage,'intIdStatusPaiement'=>1],'fature');
                if(  !empty($factureObjet ) ){
                    $montant    = $montant + $factureObjet->montant;
                }
            }
        }
        return $montant;
    }


    // Calcul des totaux Montant Non Payés
        function calulMontantTotalNonPayerPointEncodage($filter,$pointEncodage){
        $objectVec  = $this->getAllFilteredObject('trucvehicule',[$filter=>$pointEncodage,'sortie_confirm'=>0]);
        $montant    = 0;
        if($objectVec){
            foreach ($objectVec as $key => $obj) {
                $factureObjet = $this->getObjectFieldValue(['intIdTruckVehicule'=>$obj->id,'intIdPointEncodage'=>$pointEncodage,'intIdStatusPaiement'=>0],'fature');
                if(  !empty($factureObjet ) ){
                    $montant    = $montant + $factureObjet->montant;
                }
            }
        }
        return $montant;
    }

    // Calcul des totaux Montant Non Payés
        function calulMontantManqueTotalPointEncodage($filter,$pointEncodage){
        $objectVec  = $this->getAllFilteredObject('trucvehicule',$filter);
        $montant    = 0;
        if($objectVec){
            foreach ($objectVec as $key => $obj) {
                $factureObjet = $this->getObjectFieldValue(['intIdTruckVehicule'=>$obj->id,'intIdPointEncodage'=>$pointEncodage,'intIdStatusPaiement'=>0],'fature');
                if(  !empty($factureObjet ) ){
                    $montant    = $montant + $factureObjet->montant;
                }
            }
        }
        return $montant;
    }


    // Calcul des totaux  Payés
        function calulTotalPayerPointEncodage($filter,$pointEncodage){
        $objectVec  = $this->getAllFilteredObject('trucvehicule',[$filter=>$pointEncodage]);
        $cpt    = 0;
        if($objectVec){
            foreach ($objectVec as $key => $obj) {
                $factureObjet = $this->getObjectFieldValue(['intIdTruckVehicule'=>$obj->id,'intIdPointEncodage'=>$pointEncodage,'intIdStatusPaiement'=>1],'fature');
                if(  !empty($factureObjet ) ){
                    $cpt++;
                }
            }
        }
        return $cpt;
    }


    // Calcul des totaux  Payés
        function calulTotalNonPayerPointEncodage($filter,$pointEncodage){
        $objectVec  = $this->getAllFilteredObject('trucvehicule',[$filter=>$pointEncodage,'sortie_confirm'=>0]);
        $cpt    = 0;
        if($objectVec){
            foreach ($objectVec as $key => $obj) {
                $factureObjet = $this->getObjectFieldValue(['intIdTruckVehicule'=>$obj->id,'intIdPointEncodage'=>$pointEncodage,'intIdStatusPaiement'=>0],'fature');
                if(  !empty($factureObjet ) ){
                    $cpt++;
                }
            }
        }
        return $cpt;
    }

    
    // Calcul des totaux  Payés
        function calulTotalManquePointEncodage($filter,$pointEncodage){
        $objectVec  = $this->getAllFilteredObject('trucvehicule',$filter);
        $cpt    = 0;
        if($objectVec){
            foreach ($objectVec as $key => $obj) {
                $factureObjet = $this->getObjectFieldValue(['intIdTruckVehicule'=>$obj->id,'intIdPointEncodage'=>$pointEncodage,'intIdStatusPaiement'=>0],'fature');
                if(  !empty($factureObjet ) ){
                    $cpt++;
                }
            }
        }
        return $cpt;
    }

}

