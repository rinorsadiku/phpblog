<?php  

class Like extends DatabaseObject {

    static public $table_name = "likes";
    static public $columns = ['u_id', 'u_username', 'post_id', 'l_id', 'l_username'];
    
    public $id;
    public $u_id;
    public $u_username;
    public $post_id;
    public $l_id;
    public $l_username;

    public function __construct($args=[]) {
        $this->u_id = $args['u_id'] ?? 0;
        $this->u_username = $args['u_username'] ?? "";
        $this->post_id = $args['post_id'] ?? 0;
        $this->l_id = $args['l_id'] ?? 0;
        $this->l_username = $args['l_username'] ?? "";
    }

    static public function is_liked($u_id, $post_id, $l_id) {
        $sql = "SELECT * FROM likes ";
        $sql .= "WHERE ";
        $sql .= "u_id = '".self::$database->escape_string($u_id)."' ";
        $sql .= "AND ";
        $sql .= "post_id = '".self::$database->escape_string($post_id)."' ";
        $sql .= "AND ";
        $sql .= "l_id = '".self::$database->escape_string($l_id)."' ";
        $result = self::$database->query($sql);
        $obj_array = $result->fetch_assoc();
        if(!empty($obj_array)) {
            return true;
        } else {
            return false;
        }
    }
    
    public function delete($u_id, $post_id="", $l_id="") {
        if($post_id != "" && $l_id != "") {
            $sql = "DELETE FROM likes ";
            $sql .= "WHERE ";
            $sql .= "u_id = '".self::$database->escape_string($u_id)."' ";
            $sql .= "AND ";
            $sql .= "post_id = '".self::$database->escape_string($post_id)."' ";
            $sql .= "AND ";
            $sql .= "l_id = '".self::$database->escape_string($l_id)."' ";
            $sql .= "LIMIT 1";
            $result = self::$database->query($sql);
            return $result;
        } else {
            return parent::delete($u_id);
        }
    }

    static public function count_likes_per_post($u_id, $post_id) {
        $sql = "SELECT COUNT(id) FROM likes ";
        $sql .= "WHERE ";
        $sql .= "u_id = '".self::$database->escape_string($u_id)."' ";
        $sql .= "AND ";
        $sql .= "post_id = '".self::$database->escape_string($post_id)."'";
        $result = self::$database->query($sql); 
        $obj_array = $result->fetch_assoc();
        if(!empty($obj_array)) {
            return array_shift($obj_array);
        } else {
            return false;
        }
    }

}





?>