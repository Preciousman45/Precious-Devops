<?php 

class tester {
    private $router = [];

    public function get(string $path, callable $habdler) {
        return $this->add('GET',$path,$handler);
    }

    public function post(string $path , callable $handler){
        return $this->add('POST',$path,$handler);

    }

    public function put (string $path, callable $handler){
        return $this->add('PUT', $path, $handler);
    }

    public function delete(string $path, callable $handler){
        return $this->add('DELETE',$path,$handler);
    }

    public function add(string $method, string $path, callable $handler) { 
        $this->router =[
            'method'=>$method,
            'path'=>$path,
            'handler'=>$handler
        ];
    }
    
    public function match(string $requestedpattern, string $requestedpath, array $param) : bool{
        $requestpattern = explode('/',trim(requestedpattern));
        $requestpath = explode('/',trim($requestedpath));

        if(count($requestpattern) !== count($requestpath)){
            return false;
        }

        foreach($requestpattern as $index => $part){
            if(str_starts_with($part,':')){
                $param[substr($part,1)] = $requestpath[$index];
            }elseif($part !== $requestpath[$index]){
                return false;
            }
        }
        return true;
    }

    public function dispatch($method,$path){
        foreach($this->router as $route){
            if($route['method'] !== $method){
                continue;
            }

            $params = [];
            if($this->match($route['path'],$path,$params)){
                ($route['handler'])($params);
                return;
            }
        }
        http_response_code(404);
        echo json_encode(['error'=>"No route for {$method} {$path}"]);
    }

}


?>