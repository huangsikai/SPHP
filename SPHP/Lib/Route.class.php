<?php
/**
 * Describe:
 * Author: Sky
 * Date: 2017/2/5
 */

namespace SPHPCore\Lib;



class Route
{


    public function resolve($request)
    {
        $uri = $_SERVER['REQUEST_URI'];
        $uriHash = md5(serialize($uri));
        if(isset($GLOBALS['SPHPResolve'.$uriHash])){
            $request = $GLOBALS['SPHPResolve'.$uriHash];
            return $request;
        }
        $GLOBALS['SPHPResolve'.$uriHash] = $request;
        $parse = parse_url($uri);
        if(empty($parse['path']))
            $parse['path'] = '/';

        $uri = self::generateUri($parse['path']);

        $routes = Config::getRoute();

        $routeMatch = isset($routes[SPHP_ROUTE_MATCH]) ? $routes[SPHP_ROUTE_MATCH] : false;   //匹配路由
        $routeBasic = isset($routes[SPHP_ROUTE_BASIC]) ? $routes[SPHP_ROUTE_BASIC] : false;  //基本路由 优先

        if(!empty($routeBasic) && is_array($routeBasic)){
            $pattern =  '/^\/(?:(\w+)(?:\/(\w+)(?:\/((?:[\w\-\+\%\=]+)*))?)?)?/i';
            $isMatch = preg_match($pattern, $uri, $match);
            if($isMatch){
                $alias = array(
                    SPHP_ROUTE_CONTROLLER => !empty($match[1]) ? $match[1] : null,
                    SPHP_ROUTE_ACTION => !empty($match[2]) ? $match[2] : null,
                    SPHP_ROUTE_TARGET => isset($match[3]) ? $match[3] : null,
                );

                foreach($routeBasic as $route){
                    foreach($route as $tag=>$map){
                        if(empty($map[0]) || empty($map[1])) continue 2;
                        if(isset($alias[$tag])){
                            if($map[0] != $alias[$tag]) continue 2;
                            $request->$tag = $map[1];
                        }
                    }
                    return $request;
                }
            }
        }

        if(!empty($routeMatch) && is_array($routeMatch)){
            foreach($routeMatch as $route){
                $tokens = $route[0];
                $default = isset($route[1]) && is_array($route[1]) ? $route[1] : array();
                $token = '';
                foreach($tokens as $k=>$v){
                    $token.=$v.'\/';
                }
                $token = rtrim($token,'\/');
                $pattern =  '/^\/'.$token.'(?:\/((?:[\w\.\-\+\%\=]+)(?:\/[\w\.\-\+\%\=]+)*))?/i';
                $isMatch = preg_match($pattern, $uri, $match);
                if($isMatch){
                    $index=1;
                    foreach($default as $key=>$value){
                        $request->$key = $value;
                    }
                    foreach($tokens as $key=>$value){
                        $request->$key = $match[$index];$index++;
                    }
                    if(isset($match[$index])){
                        $request->setTarget($match[$index]);
                    }
                    unset($match,$routeMatch);
                    return $request;
                }
            }
        }

        $pattern = '/^\/(?:(\w+)(?:\/(\w+)(?:\/((?:[\w\-\+\%\=]+)(?:\/[\w\-\+\%\=]+)*))?)?)?/i';
        $match = array();
        if(preg_match($pattern, $uri, $match)){
            !empty($match[0]) && $request->setRequestPath($match[0]);
            !empty($match[1]) && $request->setControllerName($match[1]);
            !empty($match[2]) && $request->setActionName($match[2]);
            isset($match[3]) && $request->setTarget($match[3]);
        }
        return $request;
    }

    /**
     * 生成uri
     * @param $uri
     * @return string
     */
    private static function generateUri($uri){
        $root_dir = '/'.trim(SUB_DIR,PATH_NOISE);
        if(!empty($root_dir) && strpos($uri, $root_dir) === 0){
            $len = strcmp($uri,$root_dir);
            if($len > 0){
                $uri = mb_substr($uri,-$len);
            }else{
                $uri = mb_substr($uri, strlen($root_dir));
            }
        }
        return  '/'.trim($uri,PATH_NOISE);
    }
}