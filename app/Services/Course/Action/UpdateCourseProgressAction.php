<?php

namespace App\Services\Course\Action;

use App\Contracts\HasPageInterface;
use App\Repositories\PageRepository;
use App\Repositories\UserCourseRepository;
use App\Repositories\UserPageRepository;
use App\Repositories\UserSubSyllabusRepository;
use App\Repositories\UserSyllabusRepository;
use Illuminate\Database\Eloquent\Model;
use Throwable;

class UpdateCourseProgressAction
{
    public function __construct(
        private readonly UserPageRepository        $userPageRepository,
        private readonly UserCourseRepository      $userCourseRepository,
        private readonly UserSubSyllabusRepository $userSubSyllabusRepository,
        private readonly UserSyllabusRepository    $userSyllabusRepository,
        private readonly PageRepository            $pageRepository
    )
    {
    }

    /**
     * @throws Throwable
     */
    public function run(Model&HasPageInterface $usable, int $pageNumber, int $userId): void
    {
        $this->pageRepository->transactional(function () use ($usable, $pageNumber, $userId) {
            $page = $this->pageRepository->findSimplePage($usable, $pageNumber);

            $this->userPageRepository->completePageByUserId($page->id, $userId);

            match ($usable->getMorphClass()) {
                'course' => $this->handleCompleteCourse($usable, $userId),
                'subSyllabus' => $this->handleCompleteSyllabus($usable, $userId),
            };
        });
    }

    private function handleCompleteCourse(Model&HasPageInterface $usable, int $userId): void
    {
        is_null($usable->next_page)
            ? $this->userCourseRepository->completeCourseByUserId($usable->id, $userId)
            : $this->userCourseRepository->inProgressCourseByUserId($usable->id, $userId);
    }

    private function handleCompleteSyllabus(Model&HasPageInterface $usable, int $userId): void
    {
        $subSyllabus = $usable;
        $syllabus = $subSyllabus->usable;
        $course = $syllabus->usable;

        if (is_null($subSyllabus->next_page)) {
            $this->userSubSyllabusRepository->completeSubSyllabusByUserId($subSyllabus->id, $userId);

            if (is_null($syllabus->next_sub_syllabus)) {
                $this->userSyllabusRepository->completeSyllabusByUserId($syllabus->id, $userId);

                if (is_null($course->next_syllabus)) {
                    $this->userCourseRepository->completeCourseByUserId($course->id, $userId);
                } else {
                    $this->userCourseRepository->inProgressCourseByUserId($course->id, $userId);
                }
            } else {
                $this->userSyllabusRepository->inProgressSyllabusByUserId($syllabus->id, $userId);
                $this->userCourseRepository->inProgressCourseByUserId($course->id, $userId);
            }
        } else {
            $this->userSubSyllabusRepository->inProgressSubSyllabusByUserId($subSyllabus->id, $userId);
            $this->userSyllabusRepository->inProgressSyllabusByUserId($syllabus->id, $userId);
            $this->userCourseRepository->inProgressCourseByUserId($course->id, $userId);
        }
    }
}
