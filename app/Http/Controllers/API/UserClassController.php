<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\UserClassRequest;
use App\Http\Services\UserClassService;
use App\Models\UserClass;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UserClassController extends Controller
{
    protected UserClassService $userClassService;

    public function __construct(UserClassService $userClassService)
    {
        $this->userClassService = $userClassService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $user_classes = $this->userClassService->getUserClasses()->get()->toArray();
            return sendResponse(true, 'UserClass list', $user_classes, Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('UserClass List Error: ' . $e->getMessage());
            return sendResponse(false, 'Failed to fetch UserClass list', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    // public function create()
    // {
    //     //
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserClassRequest $request)
    {
        try{
            $validated = $request->validated();
            $user_class = $this->userClassService->createUserClass($validated);
            if (!$user_class) {
                return sendResponse(false, 'Failed to create UserClass', null, Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            return sendResponse(true, 'UserClass created successfully', $user_class, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            Log::error('UserClass Create Error: ' . $e->getMessage());
            return sendResponse(false, 'Failed to create UserClass', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(UserClass $user_class)
    {
        try{
            $user_class = $this->userClassService->getUserClass($user_class->id);
            if (!$user_class) {
                return sendResponse(false, 'Failed to fetch UserClass', null, Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            return sendResponse(true, 'UserClass fetched successfully', $user_class, Response::HTTP_OK);
        }catch (\Exception $e) {
            Log::error('UserClass Fetch Error: ' . $e->getMessage());
            return sendResponse(false, 'Failed to fetch UserClass', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    // public function edit(string $id)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(UserClassRequest $request, UserClass $user_class)
    {
        try{
            $validated = $request->validated();
            $user_class = $this->userClassService->updateUserClass($user_class, $validated);
            if (!$user_class) {
                return sendResponse(false, 'Failed to update UserClass', null, Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            return sendResponse(true, 'UserClass updated successfully', $user_class, Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('UserClass Update Error: ' . $e->getMessage());
            return sendResponse(false, 'Failed to update UserClass', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserClass $user_class)
    {
        try{
            $user_class = $this->userClassService->deleteUserClass($user_class);
            if (!$user_class) {
                return sendResponse(false, 'Failed to delete UserClass', null, Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            return sendResponse(true, 'UserClass deleted successfully', $user_class, Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('UserClass Delete Error: ' . $e->getMessage());
            return sendResponse(false, 'Failed to delete UserClass', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function toggleStatus(UserClass $user_class): JsonResponse
    {
        try {
            $user_class = $this->userClassService->toggleStatus($user_class);
            return sendResponse(true, "UserClass {$user_class->status_label}  successfully", ["status" => $user_class["status"]], Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('UserClass Status Toggle Error: ' . $e->getMessage());
            return sendResponse(false, 'Failed to toggle UserClass status', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}