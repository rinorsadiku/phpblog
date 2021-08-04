<?php

class Session {

  private $user_id;
  public $username;
  private $last_login;
  public const MAX_LOGIN_PERIOD = 60*60*24; // 1 DAY

  public function __construct() {
    session_start();
    $this->check_last_stored_login();
  }

  public function login($user) {
    if ($user) {
      session_regenerate_id();
      $_SESSION['user_id'] = $this->user_id = $user->id;
      $_SESSION['username'] = $this->username = $user->username;
      $_SESSION['last_login'] = $this->user_id = time();
    }
    return true;
  }

  public function logout() {
    unset($_SESSION['user_id']);
    unset($_SESSION['username']);
    unset($_SESSION['last_login']);
    unset($this->user_id);
    unset($this->username);
    unset($this->last_login);
  }

  public function is_logged_in() {
    return isset($_SESSION['user_id']) && $this->last_login_is_recent();
  }

  public function check_last_stored_login() {
    if (isset($_SESSION['user_id'])) {
      $this->user_id = $_SESSION['user_id'];
      $this->username = $_SESSION['username'];
      $this->last_login = $_SESSION['last_login'];
    }
  }

  public function last_login_is_recent() {
    if (!isset($this->last_login)) {
      return false;
    } elseif(($this->last_login + self::MAX_LOGIN_PERIOD) < time()) {
      return false;
    } else {
      return true;
    }
  }


}
