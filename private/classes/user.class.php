<?php

class User extends DatabaseObject {

  static protected $columns = ['first_name', 'last_name', 'email', 'username', 'hashed_password', 'posts'];
  static protected $table_name = "users";

  public $id;
  public $first_name;
  public $last_name;
  public $email;
  public $username;
  public $hashed_password;
  public $password; // This will be the received password
  public $confirm_password;
  public $posts;

  protected $password_required = true;

  public function __construct($args=[]) {
    $this->first_name = $args['first_name'] ?? "";
    $this->last_name = $args['last_name'] ?? "";
    $this->email = $args['email'] ?? "";
    $this->username = $args['username'] ?? "";
    $this->password = $args['password'] ?? "";
    $this->confirm_password = $args['confirm_password'] ?? "";
  }

  public function full_name() {
    return $this->first_name ." ". $this->last_name;
  }

  public function set_hashed_password() {
    $this->hashed_password = password_hash($this->password, PASSWORD_BCRYPT);
  }

  public function create() {
    $this->set_hashed_password();
    return parent::create();
  }

  public function update() {
    if ($this->password != "") {
      $this->set_hashed_password();
    } else {
      $this->password_required = false;
    }
    return parent::update();
  }


  protected function validate() {
    $this->errors = [];

    if(is_blank($this->first_name)) {
      $this->errors[] = "first_name";
    }// } elseif (!has_length($this->first_name, array('min' => 2, 'max' => 255))) {
    //   $this->errors[] = "First name must be between 2 and 255 characters.";
    // }

    if(is_blank($this->last_name)) {
      $this->errors[] = "last_name";
    }// } elseif (!has_length($this->last_name, array('min' => 2, 'max' => 255))) {
    //   $this->errors[] = "Last name must be between 2 and 255 characters.";
    // }

    if(is_blank($this->email)) {
      $this->errors[] = "email";
    }

    // } elseif (!has_length($this->email, array('max' => 255))) {
    //   $this->errors[] = "Last name must be less than 255 characters.";
    // } elseif (!has_valid_email_format($this->email)) {
    //   $this->errors[] = "Email must be a valid format.";
    // }

    if(is_blank($this->username)) {
      $this->errors[] = "username";
    }
    // } elseif (!has_length($this->username, array('min' => 8, 'max' => 255))) {
    //   $this->errors[] = "Username must be between 8 and 255 characters.";
    // } elseif(!has_unique_username($this->username, $this->id ?? 0)) {
    //   $this->errors[] = "Username taken. Please choose another one.";
    // }

    if ($this->password_required) {
      if(is_blank($this->password)) {
        $this->errors[] = "password";
      }

      if ($this->password_required) {
        if(is_blank($this->confirm_password)) {
          $this->errors[] = "confirm_password";
        } elseif ($this->password !== $this->confirm_password) {
          $this->errors[] = "Password and confirm password must match.";
        }
      }
    }

    return $this->errors;
  }

  static public function find_by_username($username) {
    $sql = "SELECT * FROM users ";
    $sql .= "WHERE username = '".self::$database->escape_string($username)."' ";
    $obj_array = static::find_by_sql($sql);
    if (!empty($obj_array)) {
      return array_shift($obj_array);
    } else {
      return false;
    }
  }

  public function increment_posts() {
    $sql = "UPDATE users ";
    $sql .= "SET posts = posts + 1 ";
    $sql .= "WHERE id = '".self::$database->escape_string($this->id)."' ";
    $result = self::$database->query($sql);
    return $result;
  }

  public function decrement_posts() {
    $sql = "UPDATE users ";
    $sql .= "SET posts = posts - 1 ";
    $sql .= "WHERE id = '".self::$database->escape_string($this->id)."' ";
    $result = self::$database->query($sql);
    return $result;
  }

  public function count_all_posts() {
    $sql = "SELECT COUNT(id) FROM users ";
    $sql .= "WHERE id = '".self::$database->escape_string($this->id)."' ";
    $obj_array = static::find_by_sql($sql);
    if (!empty($obj_array)) {
      return array_shift($obj_array);
    } else {
      return false;
    }
  }

  public function verify_password($password) {
    return password_verify($password, $this->hashed_password);
  }

  public function verify_page_access($session_id) {
    return $session_id === $this->id;
  }

}



?>
