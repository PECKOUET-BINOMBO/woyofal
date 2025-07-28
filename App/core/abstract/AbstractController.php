<?php
namespace App\Core\Abstract;
use App\Core\Session;
use App\Core\App;
abstract class AbstractController
{
   
    protected string $layout = 'base.layout.php';
    protected ?Session $session=null;
    public function __construct()
    {
          $this->session = App::getDependencie('Session');
    
       
    }
    
    protected function render($template, $params = [])
    {
        extract($params);
        ob_start();
        require_once __DIR__ . '/../../../templates/' . $template . '.php';
        $content = ob_get_clean();
        require_once __DIR__ . '/../../../templates/layout/' . $this->layout;
       
    }
    
    abstract public  function index() ;
    abstract public function create();
    abstract public function destroy() ;
    abstract public function show();
    abstract public function edit() ;
    abstract public function store() ;



    protected function redirect($url)
    {
        header("Location: " . $url);
        exit();
    }
}