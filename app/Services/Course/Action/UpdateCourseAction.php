<?php

namespace App\Services\Course\Action;

use App\Models\Course;
use App\Repositories\CourseRepository;
use Illuminate\Contracts\Config\Repository as Config;

class UpdateCourseAction
{
    public function __construct(
        private readonly CourseRepository $courseRepository,
        private readonly Config  $config,
    )
    {
    }

    public function run(int $courseId, array $data): Course
    {
        $course = $this->courseRepository->findOrFail($courseId);

        return $this->courseRepository->transactional(function () use ($course, $data) {
            $course = $this->courseRepository->updateModel($course, $data);

            if (array_key_exists('cover_image', $data)) {
                $course->addMedia($data['cover_image'])
                    ->toMediaCollection('cover', $this->config->get('filesystems.default_private'));
            }

            return $course;
        });
    }
}
