<?php

namespace App\Http;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubmitSurveyRequest;
use App\Http\Resources\SurveyResource;
use App\Services\Survey\SurveyAuthenticatorService;
use App\Services\Survey\SurveyService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;
use function Sentry\captureException;

class SurveyController extends Controller
{
    public function __construct(
        private readonly SurveyService     $surveyService,
        private SurveyAuthenticatorService $surveyAuthenticatorService
    )
    {
    }

    public function getItems(int $identifier, Request $request): JsonResponse
    {
        try {
            $decryptedToken = $this->surveyAuthenticatorService
                ->setReference($request->reference)
                ->decrypt();

            $surveyWithItems = $this->surveyService->getItems(
                $decryptedToken->getSurveyId(),
                $decryptedToken->getUserId()
            );
        } catch (Throwable $th) {
            captureException($th);

            return $this->error();
        }

        return $this->success(SurveyResource::make($surveyWithItems));
    }

    public function submit(int $identifier, SubmitSurveyRequest $request): JsonResponse
    {
        try {
            $decryptedToken = $this->surveyAuthenticatorService
                ->setReference($request->reference)
                ->decrypt();

            $this->surveyService->submit(
                $decryptedToken->getSurveyId(),
                $decryptedToken->getUserId(),
                $request->questions
            );

        } catch (Throwable $th) {
            captureException($th);

            return $this->error();
        }

        return $this->created();
    }
}
