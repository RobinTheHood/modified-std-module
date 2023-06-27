<?php

declare(strict_types=1);

namespace RobinTheHood\ModifiedStdModule\Classes;

use RobinTheHood\ModifiedStdModule\Classes\ControllerInterface;

class StdController implements ControllerInterface
{
    /**
     * @var ControllerInterface[] $controllers
     */
    private $controllers = [];

    public function __construct()
    {
        $this->addController($this);
    }

    public function invoke()
    {
        $action = $this->getAction();
        if (!$action) {
            $action = 'Index';
        }

        $invokeMethod = 'invoke' . ucfirst($action);

        foreach ($this->controllers as $controller) {
            $controller->preInvoke();
            if (method_exists($controller, $invokeMethod)) {
                $controller->$invokeMethod();
            }
            $controller->postInvoke();
        }
    }

    /**
     * @return string name of action
     */
    public function getAction(): string
    {
        $action = $_GET['action'] ?? '';
        if (!$action) {
            $action = $_POST['action'] ?? '';
        }
        return $action;
    }

    public function addController(ControllerInterface $contoller): void
    {
        $this->controllers[] = $contoller;
    }

    public function preInvoke()
    {
    }

    public function postInvoke()
    {
    }

    protected function echoJson(array $array): void
    {
        header('Content-Type: application/json');
        echo \json_encode($array);
    }

    protected function getArrayFromJsonPost()
    {
        $json = file_get_contents('php://input');
        $array = \json_decode($json, true);
        return $array;
    }
}
