<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Traits\ApiResponser;
use App\Services\User1Service;

class User1Controller extends Controller
{
    use ApiResponser;

    public $user1Service;

    public function __construct(User1Service $user1Service)
    {
        $this->user1Service = $user1Service;
    }

    public function index()
    {
        return $this->successResponse($this->user1Service->getUsers1());
    }

    public function add(Request $request)
    {
        return $this->successResponse(
            $this->user1Service->createUser1($request->all()),
            Response::HTTP_CREATED
        );
    }

    public function show($id)
    {
        return $this->successResponse($this->user1Service->getUser1($id));
    }

    public function update(Request $request, $id)
    {
        return $this->successResponse(
            $this->user1Service->updateUser1($id, $request->all())
        );
    }

    public function delete($id)
    {
        return $this->successResponse($this->user1Service->deleteUser1($id));
    }
}
