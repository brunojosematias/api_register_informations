<?php
// [x] Refatorar códigos de parâmetros
// [] Criar variável de requisição para receber os parâmetros e o corpo da requisição
// [] Criar variável de resposta do servidor
// [] implementar autorização de rotas

class Routes
{
  private $method;
  private $path;
  private $paramRoute;

  private $paramsGet = [''];
  private $paramsPost = [''];
  private $paramsPut = [''];
  private $paramsDelete = [''];

  public function __construct($method, $path, $param)
  {
    $this->method = $method;
    $this->path = $path;
    $this->paramRoute = $param;
  }

  public function get($endpoint, $callbackFunction)
  {
    $this->paramsGet[] = $endpoint;

    if ($this->method === 'GET' && !$this->paramRoute) {
      if ($this->execute($this->method, $this->path)) {
        echo json_encode($callbackFunction());

        exit;
      }
    }

    if ($this->method === 'GET' && $this->paramRoute) {
      if ($this->execute($this->method, $this->path)) {
        echo json_encode($callbackFunction($this->paramRoute));

        exit;
      }
    }
  }

  public function post($endpoint, $callbackFunction)
  {
    $this->paramsPost[] = $endpoint;

    if ($this->method === 'POST' && !$this->paramRoute) {


      if ($this->execute($this->method, $this->path) && !$this->paramRoute) {
        echo json_encode($callbackFunction($_POST));

        exit;
      }
    }
  }

  public function put($endpoint, $callbackFunction)
  {
    $this->paramsPut[] = $endpoint;

    if ($this->method === 'PUT' && $this->paramRoute) {
      if ($this->execute($this->method, $this->path)) {

        $_PUT = null;
        parse_str(file_get_contents('php://input'), $_PUT);

        echo json_encode($callbackFunction($_PUT, $this->paramRoute));

        exit;
      }
    }
  }

  public function delete($endpoint, $callbackFunction)
  {
    $this->paramsDelete[] = $endpoint;

    if ($this->method === 'DELETE' && $this->paramRoute) {
      if ($this->execute($this->method, $this->path)) {
        echo json_encode($callbackFunction($this->paramRoute));

        exit;
      }
    }
  }

  private function execute($httpMethod, $route)
  {
    $route = '/' . $route;

    if ($httpMethod === 'GET' && !$this->paramRoute) {
      $index = array_search($route, $this->paramsGet);
      if ($index > 0) {
        if ($this->paramsGet[$index] === $route) {

          return true;
        }
      } else {
        http_response_code(404);
      }
    }

    if ($httpMethod === 'GET' && $this->paramRoute) {
      $identifier = '';

      foreach ($this->paramsGet as $param) {
        if (str_contains($param, ":")) {
          $identifier = substr($param, strrpos($param, ":"));
        }
      }

      $route = substr($route, strpos($route, "/"), strrpos($route, "/")) . "/" . $identifier;

      $index = array_search($route, $this->paramsGet);
      if ($index > 0) {
        http_response_code(200);

        if ($this->paramsGet[$index] === $route) {

          return true;
        }
      } else {
        http_response_code(404);
      }
    }

    if ($httpMethod === 'POST' && !$this->paramRoute) {

      $index = array_search($route, $this->paramsPost);

      if ($index > 0) {
        if ($this->paramsPost[$index] === $route) {

          return true;
        }
      } else {
        http_response_code(404);
      }
    }

    if ($httpMethod === 'PUT' && $this->paramRoute) {
      $identifier = '';

      foreach ($this->paramsPut as $param) {
        if (str_contains($param, ":")) {
          $identifier = substr($param, strrpos($param, ":"));
        }
      }

      $route = substr($route, strpos($route, "/"), strrpos($route, "/")) . "/" . $identifier;

      $index = array_search($route, $this->paramsPut);
      if ($index > 0) {
        http_response_code(200);

        if ($this->paramsPut[$index] === $route) {
          return true;
        }
      } else {
        http_response_code(404);
      }
    }

    if ($httpMethod === 'DELETE') {
      $identifier = '';

      foreach ($this->paramsDelete as $param) {
        if (str_contains($param, ":")) {
          $identifier = substr($param, strrpos($param, ":"));
        }
      }

      $route = substr($route, strpos($route, "/"), strrpos($route, "/")) . "/" . $identifier;

      $index = array_search($route, $this->paramsDelete);
      if ($index > 0) {
        http_response_code(200);

        if ($this->paramsDelete[$index] === $route) {

          return true;
        }
      } else {
        http_response_code(404);
      }
    }
  }
}

$path = "/" . $_GET['path'];
$param = '';
if (substr_count($path, "/") >= 3) {
  $param = substr($path, strrpos($path, "/") + 1);
}

$route = new Routes($_SERVER['REQUEST_METHOD'], $_GET['path'],  $param);
