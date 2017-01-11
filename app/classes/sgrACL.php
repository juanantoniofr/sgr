<?php  

class sgrACL {
  /* :) 1-5-2017 */
  /**
    *
    * // Devuelve en formato json los roles con acceso a un recurso
    *
    * @param $modo int (1|0) gestión de soliticitudes de reserva atendida o desantendida 
    * @param $roles array
    *
    * @return $acl string 
  */
  public static function buildJsonAcl($modo,$roles,$maxhd = array()){

    $acl = array('r' => '',
                 'm' => '0',//por defecto gestión Atendida de las solicitudes de uso.
                 'maxhd' => $maxhd);
    $acl['m'] = $modo;
    $roles[] = Config::get('options.idroladministrador'); //Administrador tiene accseso
    $listIdRolesConAcceso = implode(',',$roles);
    $acl['r'] = $listIdRolesConAcceso;

    return json_encode($acl);
  }

}

?>