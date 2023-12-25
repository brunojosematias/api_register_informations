<?php

class UserController
{
  private $userDao;

  public function __construct(UserDao $userDao)
  {
    $this->userDao = $userDao;
  }


  public function index()
  {
    $result = $this->userDao->findAll();
    if ($result) {
      return $result;
    } else {
      return [];
    }

    return $result;
  }

  public function show($id)
  {
    $user = $this->userDao->findUnique($id);
    if ($user) {
      return $user;
    } else {
      http_response_code(404);
      return [
        'status' => 404,
        'message' => 'User not found!'
      ];
    }
  }

  public function store($datas)
  {
    $name = $datas['name'];
    $phone = $datas['phone'];
    $email = $datas['email'];
    $password = $datas['password'];

    if (!$name) {
      return [
        'status' => 400,
        'error' => 'Name is required!'
      ];
    }

    $validEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
    if (!$validEmail) {
      http_response_code(400);

      return [
        'status' => 400,
        'error' => 'Invalid email!'
      ];
    }

    if (!$email) {
      http_response_code(400);

      return [
        'status' => 400,
        'error' => 'E-mail is required!'
      ];
    }

    if (!$password) {
      http_response_code(400);

      return [
        'status' => 400,
        'error' => 'Password is required!'
      ];
    }

    $emailExists = $this->userDao->findEmail($email);
    if ($emailExists) {
      http_response_code(400);

      return [
        'status' => 400,
        'error' => 'This e-mail is already in use!'
      ];
    }

    $phoneExists = $this->userDao->findPhone($phone);
    if ($phoneExists) {
      http_response_code(400);

      return [
        'status' => 400,
        'error' => 'This phone is already in use!'
      ];
    }

    $addUSer = $this->userDao->create($datas);

    if ($addUSer) {
      return [
        'status' => 200,
        'message' => 'Success!'
      ];
    } else {
      return [
        'status' => 400,
        'message' => 'Error!'
      ];
    }
  }

  public function update($datas, $id)
  {
    $email = $datas['email'];

    $user = $this->userDao->findUnique($id);
    if (!$user) {
      http_response_code(404);
      return [
        'status' => 404,
        'message' => 'User not found!'
      ];
    }


    if ($email) {
      $validEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
      if (!$validEmail) {
        http_response_code(400);

        return [
          'status' => 400,
          'error' => 'Invalid email!'
        ];
      }

      $emailExists = $this->userDao->findEmail($email);
      if ($emailExists && $emailExists->id !== $id) {
        http_response_code(400);

        return [
          'status' => 400,
          'error' => 'This e-mail is already in use!'
        ];
      }
    }

    $updateUser = $this->userDao->update($datas, $id);

    if ($updateUser) {
      return [
        'status' => 200,
        'message' => 'Success!'
      ];
    } else {
      return [
        'status' => 400,
        'message' => 'Error!'
      ];
    }


    // return $updateUser;
  }

  public function destroy($id)
  {
    $deleteUser = $this->userDao->delete($id);

    http_response_code(204);

    return [
      'status' => 204,
      'message' => 'Deleted user!'
    ];
  }
}
