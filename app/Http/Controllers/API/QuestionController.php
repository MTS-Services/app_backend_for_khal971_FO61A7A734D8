<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\QuestionRequest;
use App\Http\Services\QuestionService;
use App\Http\Services\QuestionTypeService;
use App\Http\Services\TopicService;
use App\Models\Question;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class QuestionController extends Controller
{
    protected QuestionService $questionService;
    protected QuestionTypeService $questionTypeService;
    protected TopicService $topicService;

    public function __construct(QuestionService $questionService, QuestionTypeService $questionTypeService, TopicService $topicService)
    {
        $this->questionService = $questionService;
        $this->questionTypeService = $questionTypeService;
        $this->topicService = $topicService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try{
            $questions = $this->questionService->getQuestions()->with('topic.course.subject', 'questionType')->get();
            return sendResponse(true, 'Question list fetched successfully', $questions, Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Question List Error: ' . $e->getMessage());
            return sendResponse(false, 'Failed to fetch question list', null, Response::HTTP_INTERNAL_SERVER_ERROR);
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
    public function store(QuestionRequest $request)
    {
        try{
            $validated = $request->validated();
            $question = $this->questionService->createQuestion($validated);
            if (!$question) {
                return sendResponse(false, 'Failed to create question', null, Response::HTTP_INTERNAL_SERVER_ERROR);
            }
            return sendResponse(true, 'Question created successfully', $question, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            Log::error('Question Create Error: ' . $e->getMessage());
            return sendResponse(false, 'Failed to create question', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Question $question): JsonResponse
    {
        try{
            $question = $this->questionService->getQuestion($question->id)->load('topic.course.subject', 'questionType');
            if (!$question) {
                return sendResponse(false, 'Question not found', null, Response::HTTP_NOT_FOUND);
            }
            return sendResponse(true, 'Question fetched successfully', $question, Response::HTTP_OK);   
        } catch (\Exception $e) {
            Log::error('Question Fetch Error: ' . $e->getMessage());
            return sendResponse(false, 'Failed to fetch question', null, Response::HTTP_INTERNAL_SERVER_ERROR);
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
    public function update(QuestionRequest $request, Question $question): JsonResponse
    {
        try{
            $question = $this->questionService->getQuestion($question->id);
            if (!$question) {
                return sendResponse(false, 'Question not found', null, Response::HTTP_NOT_FOUND);
            }
            $validated = $request->validated();
            $question = $this->questionService->updateQuestion($question, $validated);
            return sendResponse(true, 'Question updated successfully', $question, Response::HTTP_OK);
        }catch(\Exception $e) {
            Log::error('Question Update Error: ' . $e->getMessage());
            return sendResponse(false, 'Failed to update question', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Question $question): JsonResponse
    {
        try{
            $question = $this->questionService->getQuestion($question->id);
            if (!$question) {
                return sendResponse(false, 'Question not found', null, Response::HTTP_NOT_FOUND);
            }
            $this->questionService->deleteQuestion($question);
            return sendResponse(true, 'Question deleted successfully', null, Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Question Delete Error: ' . $e->getMessage());
            return sendResponse(false, 'Failed to delete question', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function toggoleStatus(Question $question): JsonResponse
    {
        try{
            $question = $this->questionService->getQuestion($question->id);
            if (!$question) {
                return sendResponse(false, 'Question not found', null, Response::HTTP_NOT_FOUND);
            }
            $question = $this->questionService->toggleStatus($question);
            return sendResponse(true, "Question {$question->status_label}  successfully", null, Response::HTTP_OK);
        } catch (\Exception $e) {
            Log::error('Question Status Toggle Error: ' . $e->getMessage());
            return sendResponse(false, 'Failed to toggle question status', null, Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
