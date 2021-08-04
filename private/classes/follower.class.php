<?php

class Follower extends DatabaseObject {

  static public $table_name = "followers";
  static public $columns = ['u_id', 'u_username', 'f_id', 'f_username'];

  public $id;
  public $u_id;
  public $u_username;
  public $f_id;
  public $f_username;

  public function __construct($args=[]) {
    $this->u_id = $args['u_id'] ?? 0;
    $this->u_username = $args['u_username'] ?? "";
    $this->f_id = $args['f_id'] ?? 0;
    $this->f_username = $args['f_username'] ?? "";
  }

  static public function count_followers_by_user($u_id) {
    $sql = "SELECT COUNT(id) FROM followers ";
    $sql .= "WHERE u_id = '".$u_id."' ";
    $result = static::$database->query($sql);
    $obj_array = $result->fetch_assoc();
    if (!empty($obj_array)) {
      return array_shift($obj_array);
    } else {
      return false;
    }
  }

  static public function is_followed($u_id, $f_id) {
    $sql = "SELECT * FROM followers ";
    $sql .= "WHERE ";
    $sql .= "u_id = '".$u_id."' ";
    $sql .= "AND ";
    $sql .= "f_id = '".$f_id."' ";
    $result = static::$database->query($sql);
    $obj_array = $result->fetch_assoc();
    if (!empty($obj_array)) {
      return true;
    } else {
      return false;
    }
  }

  public function delete($id, $f_id="") {
    if ($f_id != "") {
      $sql = "DELETE FROM followers ";
      $sql .= "WHERE u_id = '".static::$database->escape_string($id)."' ";
      $sql .= "AND ";
      $sql .= "f_id = '".static::$database->escape_string($f_id)."' ";
      $result = static::$database->query($sql);
      return $result;
    } else {
      return parent::delete($id);
    }
  }


}

?>
