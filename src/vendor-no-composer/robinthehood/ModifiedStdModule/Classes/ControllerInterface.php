<?php

declare(strict_types=1);

namespace RobinTheHood\ModifiedStdModule\Classes;

interface ControllerInterface
{
    public function preInvoke();

    public function postInvoke();
}
