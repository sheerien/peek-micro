<?php
declare(strict_types = 1);
namespace Micro\Peek\Router;

use Micro\Peek\Router\RouterInterface;

class Router implements RouterInterface
{
    /**
     * return an array of routers from our routing table.
     * 
     * @var array
     */
    protected array $routers=[];
    
    /**
     * return an array of route params.
     * 
     * @var array
     */
    protected array $params=[];
    
    /**
     * Adds a suffix onto the controller name.
     * 
     * @var string
     */
    protected string $controllerSuffix = 'controller';
    
	/**
	 * simple add a route to the routing table.
	 *
	 * @param string $route 
	 * @param array $params 
	 */
	public function add(string $route, array $params=[]): void 
    {
        $this->routes[$route] = $params;
	}
	
	/**
	 * Dispatch route and create controller object and execute the default method
	 * on that controller object.
	 *
	 * @param string $url 
	 */
	public function dispatch(string $url): void 
    {
        if($this->match($url)){
            $controllerString = $this->params['controller'];
            $controllerString = $this->transformUpperCamelCase($controllerString);
            $controllerString = $this->getNamespace($controllerString);
            if(class_exists($controllerString)){
                $controllerObject = new $controllerString();
                $action = $this->params['action'];
                $action = $this->transformCamelCase($action);
                if(is_callable([$controllerObject, $action])){
                    $controllerObject->$action();
                }else{
                    throw new \Exception();
                }
            }else{
                throw new \Exception();
            }
        }else{
            //404 not found
            throw new \Exception();
        }
	}
    
    /**
     * Transform from any format string to upperCamelCase format.
     * 
     * @param string $string
     * @return string
     */
    private function transformUpperCamelCase(string $string): string
    {
        return str_replace(' ', '', ucwords(str_replace('-', '', $string)));
    }
    
    /**
     * Transform from any format string to camelCase format.
     * 
     * @param string $string
     * @return string
     */
    private function transformCamelCase(string $string): string
    {
        return lcfirst($this->transformUpperCamelCase($string));
    }
    /**
     * Get the namespace for the controller class,
     * the namespace defined within the route parameters,
     *  only if it was added.
     * 
     * @param string $string
     * @return string
     */
    private function getNamespace(string $string): string
    {
        $namespace = 'App\Controller\\';
        if(array_key_exists('namespace', $this->params)){
            $namespace .= $this->params['namespace'] . '\\';
        }
        return $namespace;
    }
    
    /**
     * Match the route to the routes in the routing table ,
     * setting the $this->params property if a route is found.
     * 
     * @param string $url
     * @return bool
     */
    private function match(string $url):bool
    {
        foreach ($this->routes as $route => $params) {
            if(preg_match($route, $url, $matches)) {
                foreach ($matches as $key => $param) {
                    if(is_string($key)){
                        $params[$key] = $param;
                    }
                }
                $this->params = $params;
                return true;
            }
        }
        return false;
    }
}