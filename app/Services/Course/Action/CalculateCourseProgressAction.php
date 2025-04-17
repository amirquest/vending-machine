<?php

namespace App\Services\Course\Action;

use App\Repositories\CourseRepository;
use App\Repositories\UserPageRepository;

class CalculateCourseProgressAction
{
    public function __construct(
        private readonly CourseRepository   $courseRepository,
        private readonly UserPageRepository $userPageRepository,
    )
    {
    }

    public function run(int $courseId, int $userId): float|int
    {
        $course = $this->courseRepository->findByUserWithRelations($courseId);
        $userPage = $this->userPageRepository->getByUser($userId);


        $completedPages = 0;
        $totalPages = $course->syllabuses->reduce(function ($totalCarry, $syllabuses) use (&$completedPages, $userPage) {
            return $totalCarry + $syllabuses->subSyllabuses->reduce(function ($subCarry, $subSyllabuses) use (&$completedPages, $userPage) {
                    $pages = $subSyllabuses->pages;
                    $completedPages += $pages->filter(function ($page) use ($userPage) {
                        return is_not_null((clone $userPage)->where('page_id', $page->id)->first());
                    })->count();

                    return $subCarry + $pages->count();
                }, 0);
        }, 0);


        return $totalPages > 0 ? ceil(($completedPages / $totalPages) * 100) : 0;
    }
}
