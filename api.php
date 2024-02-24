<?php
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Credentials: true');
header('Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, Authorization, Accept, X-Request-Width, x-xsrf-token');
header('Content-Type: application/json; charset=utf-8');

include ('bd.php');
$response = array();
$post = json_decode(file_get_contents('php://input'), true);

//////////------CREAR USUARIO--------////////

if ($post['accion'] == 'registrarPersona') {
    $sentencia = sprintf(
        "INSERT INTO persona (ci_persona, email_persona, nom_persona, ape_persona, usu_persona, cla_persona) 
        values ('%s', '%s', '%s', '%s', '%s', '%s')",
        $post['cedula'], $post['email'], $post['nombre'], $post['apellido'], $post['usuario'], $post['clave']
    );
    $result = mysqli_query($mysql, $sentencia);
    if ($result) {
        $response['estado'] = true;
    } else {
        $response['estado'] = false;
    }
    echo json_encode($response);
}
//////////------INICIAR SESIÓN VALIDO--------////////
if ($post['accion'] == 'login') {
    $sentencia = sprintf(
      "SELECT * FROM persona WHERE usu_persona='%s' AND cla_persona='%s'",
      $post['usuario'],
      $post['password']
    );
    $result = mysqli_query($mysql, $sentencia);
  
    if (mysqli_num_rows($result) == 1) {
      $datos = mysqli_fetch_assoc($result);
      $envio = json_encode(array('estado' => true, 'cod_persona' => $datos['cod_persona']));
    } else {
      $envio = json_encode(array('estado' => false));
    }
  
    echo $envio;
}

////////////////////CRUD CONTACTOS/////////////////
//////////////------AGREGAR CONTACTOS-------///////////////////////
if ($post['accion']=='registrarContacto')
{
$sentencia =sprintf(
    "INSERT INTO contacto (cod_contacto, nom_contacto, ape_contacto, tel_contacto, per_cod_persona) 
    values ('%s', '%s', '%s', '%s', '%s')",
$post['codigo'], $post['nombre'],$post['apellido'],$post['telefono'], $post['id_persona']);
$result=mysqli_query($mysql,$sentencia);
if($result)
{
    $envio=json_encode(array('estado'=>true));
}
else
{
    $envio=json_encode(array('estado'=>false));
}
echo $envio;
}
//////////////------LISTAR CONTACTOS-------///////////////////////
if ($post['accion'] == 'listarContactos') {
    $id_persona = $post['id_persona'];
    $sentencia = "SELECT * FROM contacto WHERE persona_cod_persona='$id_persona'";
    $result = mysqli_query($mysql, $sentencia);
    $f = mysqli_num_rows($result);
    $contacto = array();
    while ($row = mysqli_fetch_assoc($result)) {
        array_push($contacto, array(
            'codigo' => $row['cod_contacto'],
            'nombre'  => $row['nom_contacto'],
            'apellido'  => $row[' ape_contacto'],   
            'telefono'  => $row['tel_contacto'],
            // 'persona'  => $row['per_cod_persona']
        ));
    }
    if ($f > 0) {
        $envio = json_encode(array('estado'=>true, 'contacto'=>$contacto));
    } else {
        $envio = json_encode(array('estado'=>false));
    }
    echo $envio;
}
///////-----------ELIMINAR --------------///////////
if ($post['accion']=='deleteContacto')
{
$sentencia =sprintf(
    "DELETE FROM contacto WHERE cod_contacto='%s'",$post['idContacto']);
$result=mysqli_query($mysql,$sentencia);
if($result)
{
    $envio=json_encode(array('estado'=>true));
}
else
{
    $envio=json_encode(array('estado'=>false));
}
echo $envio;
}
////////-----------ACTUALIZAR --------------///////////

if ($post['accion'] == 'updateContacto') {
    $sentencia = sprintf(
        "UPDATE contacto SET cod_contacto='%s', nom_contacto='%s', ape_contacto='%s', tel_contacto='%s' WHERE cod_contacto='%s'",
        $post['codigo'],$post['nombre'],$post['apellido'],$post['telefono'],$post['id_contacto']
    );
    $result = mysqli_query($mysql, $sentencia);
    if ($result) {
        $envio = json_encode(array('estado' => true));
    } else {
        $envio = json_encode(array('estado' => false));
    }
    echo $envio;
}