<?php

require_once '../../../includes/conexion.php';

if(!empty($_POST)){
    if(trim($_POST['nota']) == '') {
        $respuesta = array('status' => false,'msg' => 'Todos los campos son necessarios');
    } else{
        $ideventregada = $_POST['ideventregada'];
        $nota = $_POST['nota'];

        $sqlInsert = 'INSERT INTO notas (ev_entregada_id,valor_nota) VALUES (?,?)';
        $queryInsert = $pdo->prepare($sqlInsert);
        $request = $queryInsert->execute(array($ideventregada,$nota));
       
        if($request > 0){
            $respuesta = array('status' => true,'msg' => 'Nota guardada correctamente');
        }

    }
    echo json_encode($respuesta,JSON_UNESCAPED_UNICODE);
}

?>