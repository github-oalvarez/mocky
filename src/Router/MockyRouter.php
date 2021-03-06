<?php

namespace Karriere\Mocky\Router;

use Karriere\Mocky\Models\State;
use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;

class MockyRouter extends Router
{
    public function setup(App $app, State $state)
    {
        $mockyRouter = $this;

        $app->get('/setup/{scope}/{folder}[/{name}]', function (Request $request, Response $response) use ($state, $mockyRouter) {
            $testName = $request->getAttribute('name', null);
            $folder = $request->getAttribute('folder');

            if (is_null($testName)) {
                $testName = $folder;
                $folder = null;
            } else {
                $testName = $folder.DIRECTORY_SEPARATOR.$testName;
            }

            $testScope = $request->getAttribute('scope', null);

            $state->setActiveTest($testName, $testScope);

            $body = json_encode([
                'status'  => 200,
                'message' => sprintf("test name set to '%s' - test scope set to '%s'", $testName, $testScope),
            ]);

            return $mockyRouter->respond($response, 200, 'application/json', $body);
        });
    }
}
