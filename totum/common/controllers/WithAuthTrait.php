<?php


namespace totum\common\controllers;

use Psr\Http\Message\ServerRequestInterface;
use totum\common\Auth;

trait WithAuthTrait
{
    protected function __run($action, ServerRequestInterface $request)
    {
        $this->User = Auth::webInterfaceSessionStart($this->Config);

        if ($this->User) {
            $this->__actionRun($action, $request);
        } else {
            $this->__UnauthorizedAnswer($request);
        }
    }

    protected function __UnauthorizedAnswer(ServerRequestInterface $request)
    {
        if ($request->getParsedBody()['ajax'] ?? false) {
            echo json_encode(['error' => $this->Config->getLangObj()->translate('Authorization lost.')]);
            die;
        }
        header('HTTP/1.0 401 Unauthorized');
        header('location: /Auth/Login/?from=' . urlencode($_SERVER['REQUEST_URI']));
        die;
    }
}
