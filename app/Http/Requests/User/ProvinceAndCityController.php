<?php

namespace App\Http\Requests\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\User\ProvinceResource;
use App\Repositories\ProvinceRepository;
use Illuminate\Http\JsonResponse;

class ProvinceAndCityController extends Controller
{
    public function __construct(private ProvinceRepository $provinceRepository)
    {
    }

    public function index(
    ): JsonResponse {
        return $this->success(ProvinceResource::collection($this->provinceRepository->getProvincesWithCities()));
    }
}
