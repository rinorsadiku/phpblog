<?php

class Blog extends DatabaseObject {

  static protected $columns = ['u_id', 'title', 'preview', 'content', 'likes', 'unix_date'];
  static protected $table_name = "blog";

  public $id;
  public $u_id;
  public $title;
  public $preview;
  public $content;
  public $likes;
  public $unix_date;
  // public $formatted_date;

  // public function __construct() {
  //   $this->format_date();
  // }


  public function formatted_date() {
    return date("F j, Y, g:i a", $this->unix_date);
  }

  public function __construct($args=[]) {
    $this->u_id = $args['u_id'] ?? '';
    $this->title = $args['title'] ?? '';
    $this->preview = $args['preview'] ?? '';
    $this->content = $args['content'] ?? '';
    $this->liked = 0;
    $this->unix_date = time();
  }

  protected function validate() {
    $this->errors = [];

    if(is_blank($this->title)) {
      $this->errors[] = 'title';
    }

    if(is_blank($this->preview)) {
      $this->errors[] = 'preview';
    }

    if(is_blank($this->content)) {
      $this->errors[] = 'content';
    }

    return $this->errors;
  }

  static public function find_all($id="") {
    if ($id != "") {
      $sql = "SELECT * FROM blog ";
      $sql .= "WHERE u_id = '".self::$database->escape_string($id)."' ";
      $sql .= "ORDER BY unix_date DESC";
      return static::find_by_sql($sql);
    } else {
      return parent::find_all();
    }
  }
  
  static public function find_limited_blogs($per_page, $offset) {
    $sql = "SELECT * FROM blog ";
    $sql .= "ORDER BY unix_date DESC ";
    $sql .= "LIMIT {$per_page} ";
    $sql .= "OFFSET {$offset} ";
    return static::find_by_sql($sql);
  }


}



?>
