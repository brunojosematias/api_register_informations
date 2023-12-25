<?php

class UserDao
{
  private $pdo;

  public function __construct()
  {
    $this->pdo = DB::connect();
  }

  public function findAll()
  {

    $smtp = $this->pdo->prepare("SELECT * FROM users ORDER BY created_at");
    $smtp->execute();
    $users = $smtp->fetchAll(PDO::FETCH_ASSOC);

    return $users;
  }

  public function findUnique($id)
  {
    $smtp = $this->pdo->prepare("SELECT * FROM users 
    WHERE id=:id");
    $smtp->bindParam(':id', $id, PDO::PARAM_STR_CHAR);
    $smtp->execute();
    $user = $smtp->fetchObject();

    return $user;
  }

  public function findEmail($email)
  {
    $smtp = $this->pdo->prepare("SELECT * FROM users 
    WHERE email = :email");
    $smtp->bindParam(':email', $email, PDO::PARAM_STR_CHAR);
    $smtp->execute();
    $user = $smtp->fetchObject();

    return $user;
  }

  public function findPhone($phone)
  {
    $smtp = $this->pdo->prepare("SELECT * FROM users 
    WHERE phone = :phone");
    $smtp->bindParam(':phone', $phone, PDO::PARAM_STR_CHAR);
    $smtp->execute();
    $user = $smtp->fetchObject();

    return $user;
  }

  public function create($datas)
  {
    $name = $datas['name'];
    $birthData = $datas['birthData'];
    $phone = $datas['phone'];
    $email = $datas['email'];
    $password = password_hash($datas['password'], PASSWORD_DEFAULT);

    $smtp = $this->pdo->prepare("INSERT INTO users (id, name, birthData, phone, email, password, created_at) 
    VALUES (UUID(), :name, :birthData, :phone, :email, :password, NOW())");

    $smtp->bindParam(':name', $name);
    $smtp->bindParam(':birthData', $birthData);
    $smtp->bindParam(':phone', $phone);
    $smtp->bindParam(':email', $email);
    $smtp->bindParam(':password', $password);
    $result = $smtp->execute();

    return $result;
  }

  public function update($datas, $id)
  {
    $sql = "UPDATE users SET";
    foreach (array_keys($datas) as $key) {
      $sql .= " $key=:$key,";
    }
    $sql = rtrim($sql, ',');
    $sql .= " WHERE id=:id";

    $smtp = $this->pdo->prepare($sql);

    $smtp->bindParam(':id', $id);

    foreach (array_keys($datas) as $key) {
      if ($key === 'password') {
        $smtp->bindParam(":$key", password_hash($datas[$key], PASSWORD_DEFAULT));
      } else {
        $smtp->bindParam(":$key", $datas[$key]);
      }
    }

    $result = $smtp->execute();

    return $result;
  }

  public function delete($id)
  {
    $smtp = $this->pdo->prepare("DELETE FROM users WHERE id=:id");
    $smtp->bindParam(':id', $id);
    $result = $smtp->execute();

    return $result;
  }
}
