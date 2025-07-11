<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\QuestionDetailsRequest;
use App\Http\Resources\QuestionDetailResource;
use App\Http\Services\QuestionDetailsService;
use App\Models\QuestionDetails;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class QuestionDetailsController extends Controller
{
    protected QuestionDetailsService $questionDetailsService;

    public function __construct(QuestionDetailsService $questionDetailsService)
    {
        $this->questionDetailsService = $questionDetailsService;
    }
    /**
     * Display a listing of the resource.
     */
    public function topicQuestionDetails($topic_id): JsonResponse
    {
        try {
            $question_details = $this->questionDetailsService->getQuestionDetails($topic_id)->get();
            if (empty($question_details)) {
                return sendResponse(false, 'No question details found', null, Response::HTTP_NOT_FOUND);
            }
            return sendResponse(true, 'Question details fetched successfully', QuestionDetailResource::collection($question_details), Response::HTTP_OK);
        } catch (\Exception $e) {
            return sendResponse(false, $e->getMessage(), null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(QuestionDetailsRequest $request): JsonResponse
    {
        try {
            if (request()->user()->is_admin !== true) {
                return sendResponse(false, 'Unauthorized access', null, Response::HTTP_UNAUTHORIZED);
            }
            $validated = $request->validated();
            $question_detail = $this->questionDetailsService->createQuestionDetail($validated);
            if (!$question_detail) {
                return sendResponse(false, 'Failed to create question details', null, Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            return sendResponse(true, 'Question details created successfully', new QuestionDetailResource($question_detail), Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return sendResponse(false, $e->getMessage(), null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(QuestionDetails $question_detail): JsonResponse
    {
        try {
            $question_detail = $this->questionDetailsService->getQuestionDetail($question_detail->id)->load('topic');
            if (!$question_detail) {
                return sendResponse(false, 'Question details not found', null, Response::HTTP_NOT_FOUND);
            }
            return sendResponse(true, 'Question details fetched successfully', new QuestionDetailResource($question_detail), Response::HTTP_OK);
        } catch (\Exception $e) {
            return sendResponse(false, $e->getMessage(), null, Response::HTTP_INTERNAL_SERVER_ERROR);
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
    public function update(QuestionDetailsRequest $request, QuestionDetails $question_detail): JsonResponse
    {
        try {
            if (request()->user()->is_admin !== true) {
                return sendResponse(false, 'Unauthorized access', null, Response::HTTP_UNAUTHORIZED);
            }
            $validated = $request->validated();

            $question_detail = $this->questionDetailsService->updateQuestionDetail($question_detail, $validated);

            if (!$question_detail) {
                return sendResponse(false, 'Failed to update question details', null, Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            return sendResponse(true, 'Question details updated successfully', new QuestionDetailResource($question_detail), Response::HTTP_OK);
        } catch (\Exception $e) {
            return sendResponse(false, $e->getMessage(), null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            if (request()->user()->is_admin !== true) {
                return sendResponse(false, 'Unauthorized access', null, Response::HTTP_UNAUTHORIZED);
            }
            $question_detail = $this->questionDetailsService->getQuestionDetail($id);
            if (!$question_detail) {
                return sendResponse(false, 'Question details not found', null, Response::HTTP_NOT_FOUND);
            }
            $this->questionDetailsService->deleteQuestionDetail($question_detail);
            return sendResponse(true, 'Question details deleted successfully', null, Response::HTTP_OK);
        } catch (\Exception $e) {
            return sendResponse(false, $e->getMessage(), null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function toggleStatus(QuestionDetails $question_detail): JsonResponse
    {
        try {
            if (request()->user()->is_admin !== true) {
                return sendResponse(false, 'Unauthorized access', null, Response::HTTP_UNAUTHORIZED);
            }
            $question_detail = $this->questionDetailsService->toggleStatus($question_detail);
            if (!$question_detail) {
                return sendResponse(false, 'Failed to toggle status', null, Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            return sendResponse(true, 'Status toggled to ' . $question_detail->status_label . ' successfully', null, Response::HTTP_OK);
        } catch (\Exception $e) {
            return sendResponse(false, $e->getMessage(), null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
