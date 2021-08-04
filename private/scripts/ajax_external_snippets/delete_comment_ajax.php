<?php require_once('../../initialize.php');  ?>
<?php 
sleep(1);
if(!is_ajax_request()) {exit;}
$id = isset($_POST['id']) ? $_POST['id'] : 1; 
$result = Comment::delete($id);
if($result) {
    echo $id;
}
?>