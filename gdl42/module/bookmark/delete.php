<?

/***************************************************************************
                         /module/bookmark/delete.php
                             -------------------
    copyright            : (C) 2007 Lastiko Wibisono, KMRG ITB
    email                : leonhart_4321@yahoo.com
	reviewer             : Beni Rio Hermanto (benirio@kmrg.itb.ac.id)
	
 ***************************************************************************/


if (eregi("delete.php",$_SERVER['PHP_SELF'])) {
    die();
}

$act = $_POST["act"];
$arr_id = $_POST['id'];

// delete bookmark
if ((!empty($act)) and (!empty($arr_id))) {
	while (list($key,$val) = each($arr_id)){
		$gdl_db->delete("bookmark","bookmark_id=$key");
	}

}

?>