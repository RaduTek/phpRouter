<?php

class Router {

    public static function redirect($new_route) {
        return function() use ($new_route) {
            redirect($new_route);
        };
    }
    

    public static function render($target, $params = []) {
        if (is_callable($target)) {
            call_user_func($target, $params);
        } else {
            if (!str_ends_with($target, '.php'))
                $target .= '.php';
            include_once($target);
        }
        exit();
    }

    // Render a route with either a target file or a content function
    public static function route($route, $target) {
        $route = substr($route, 1);
        $route_parts = explode('/', $route);
        $request_url = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
        $request_url = substr($request_url, 1);
        $request_parts = explode('/', $request_url);

        $params = [];
        if (count($route_parts) !== count($request_parts))
            return;
        for ($i = 0; $i < count($route_parts); $i++) {
            $route_part = $route_parts[$i];
            $request_part = $request_parts[$i];

            if (strlen($route_part) > 0 && $route_part[0] === '$') {
                $route_var_name = substr($route_part, 1);
                $$route_var_name = $request_part;
                array_push($params, $request_part);
            } else if ($route_part != $request_part) {
                return;
            }
        }

        if (is_callable($target)) {
            call_user_func_array($target, $params);
        } else {
            if (!str_ends_with($target, '.php'))
                $target .= '.php';
            include_once($target);
        }
        exit();
    }

    public static function get($route, $target) {
        if ($_SERVER['REQUEST_METHOD'] === 'GET')
            self::route($route, $target);
    }

    public static function all($route, $target) {
        self::route($route, $target);
    }

    public static function post($route, $target) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST')
            self::route($route, $target);
    }

    // Must be called to be able to access static content
    public static function default() {
        $request_url = filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_URL);
        $request_url = substr($request_url, 1);
        $request_parts = explode('/', $request_url);

        // Static content provider
        if ($request_parts[0] === 'static') {
            if (file_exists($request_url) && is_file($request_url)) {
                $mime_type = mime_content_type($request_url);
                if (str_ends_with($request_url, '.php'))
                    return;
                if (str_ends_with($request_url, '.css'))
                    $mime_type = 'text/css';
                if (str_ends_with($request_url, '.js'))
                    $mime_type = 'text/javascript';
                if (str_ends_with($request_url, '.html'))
                    $mime_type = 'text/html';
                header($_SERVER["SERVER_PROTOCOL"] . " 200 OK");
                header("Cache-Control: public");
                header("Content-Type: " . $mime_type);
                header("Content-Transfer-Encoding: Binary");
                header("Content-Length: " . filesize($request_url));
                readfile($request_url);
                exit();
            }
            return;
        }
    }

    public static function notfound($target) {
        http_response_code(404);
        self::render($target);
    }
}