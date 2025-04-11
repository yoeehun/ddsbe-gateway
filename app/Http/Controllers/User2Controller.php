<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\ApiResponser;
use App\Services\User2Service;

class User2Controller extends Controller
{
    use ApiResponser;

    public $user2Service;

    public function __construct(User2Service $user2Service)
    {
        $this->user2Service = $user2Service;
    }

    public function index()
    {
        return $this->successResponse($this->user2Service->getUsers2());
    }

    public function add(Request $request)
    {
        return $this->successResponse(
            $this->user2Service->createUser2($request->all()),
            Response::HTTP_CREATED
        );
    }

    public function show($id)
    {
        return $this->successResponse($this->user2Service->getUser2($id));
    }

    public function update(Request $request, $id)
    {
        return $this->successResponse(
            $this->user2Service->updateUser2($id, $request->all())
        );
    }

    public function delete($id)
    {
        return $this->successResponse($this->user2Service->deleteUser2($id));
    }
}
