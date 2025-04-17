<?php

namespace App\Services\Course\Action;

use App\Models\Course;
use App\Repositories\CourseRepository;
use App\Services\Course\Dto\CreateCourseDto;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Throwable;


class CreateCourseAction
{
    public function __construct(
        private readonly CourseRepository $courseRepository,
        private readonly Config           $config,
    )
    {

    }

    /**
     * @throws Throwable
     */
    public function run(CreateCourseDto $createCourseDto)
    {
        return $this->courseRepository->transactional(function () use ($createCourseDto) {
            $course = $this->courseRepository->create($createCourseDto->toArray());

            $this->attachCoverImage($course, $createCourseDto->getCoverImage());

            return $course;
        });
    }


    /**
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    private function attachCoverImage(Model|Course $course, UploadedFile $coverImage): void
    {
        $course->addMedia($coverImage)
            ->toMediaCollection('cover', $this->config->get('filesystems.default_private'));
    }
}
