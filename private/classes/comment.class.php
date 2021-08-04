<?php  

class Comment extends DatabaseObject {
    
   static public $table_name = "comments";
   static public $columns = ['u_id', 'u_username', 'post_id', 'c_id', 'c_username', 'comment', 'unix_date'];

   public $id;
   public $u_id;
   public $u_username;
   public $post_id;
   public $c_id;
   public $c_username;
   public $comment;
   public $unix_date;

   public function __construct($args=[]) {
    $this->u_id = $args['u_id'] ?? 0;
    $this->u_username = $args['u_username'] ?? "";
    $this->post_id = $args['post_id'] ?? 0;
    $this->c_id = $args['c_id'] ?? 0;
    $this->c_username = $args['c_username'] ?? "";
    $this->comment = $args['comment'] ?? "";
    $this->unix_date = time();
   }

   public function formatted_date() {
    return date("F j, Y, g:i a", $this->unix_date);
  }

   static public function find_comments_by_post_id($id, $per_page, $offset) {
    $sql = "SELECT * FROM comments ";
    $sql .= "WHERE post_id = '".self::$database->escape_string($id)."' ";
    $sql .= "ORDER BY unix_date DESC ";
    $sql .= "LIMIT {$per_page} ";
    $sql .= "OFFSET {$offset} ";
    return static::find_by_sql($sql);
   }

       
   public function delete($id) {
        if($id != "") {
            $sql = "DELETE FROM comments ";
            $sql .= "WHERE ";
            $sql .= "id = '".self::$database->escape_string($id)."' ";
            $sql .= "LIMIT 1";
            $result = self::$database->query($sql);
            return $result;
        } else {
            return parent::delete($u_id);
        }
    }

}



?>