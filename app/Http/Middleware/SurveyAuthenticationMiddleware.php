<?php

namespace App\Http\Middleware;

use App\Services\Survey\Exception\SurveyAuthenticationException;
use App\Services\Survey\Exception\SurveyTokenExpiredException;
use App\Services\Survey\SurveyAuthenticatorService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SurveyAuthenticationMiddleware
{
    public function __construct(protected SurveyAuthenticatorService $surveyAuthenticatorService)
    {
    }

    /**
     * @throws SurveyAuthenticationException
     */
    public function handle(Request $request, Closure $next): Response
    {
        $reference = $request->reference;
        $surveyIdentifier = $request->identifier;

        if (is_null($reference) || is_null($surveyIdentifier)){
            throw SurveyAuthenticationException::whenSurveyIsInvalid();
        }

        $this->surveyAuthenticatorService
            ->setReference($reference)
            ->setSurveyIdentifier($surveyIdentifier)
            ->decrypt()
            ->authenticate();

        return $next($request);
    }
}
