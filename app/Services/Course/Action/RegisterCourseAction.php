<?php

namespace App\Services\Course\Action;

use App\Models\Course;
use App\Repositories\CourseInviteCodeRepository;
use App\Repositories\CourseRepository;
use App\Repositories\UserCourseRepository;
use App\Services\Course\Dto\RegisterCourseResponseDto;
use App\Services\Course\Exceptions\RegisterCourseException;
use App\Services\Payment\Payment\Request\Purchase\Dto\PurchaseRequestDto;
use App\Services\Payment\UserCoursePaymentService;
use Illuminate\Database\Eloquent\Model;
use Throwable;

class RegisterCourseAction
{
    public function __construct(
        private readonly CourseRepository           $courseRepository,
        private readonly UserCourseRepository       $userCourseRepository,
        private readonly CourseInviteCodeRepository $courseInviteCodeRepository,
        private readonly UserCoursePaymentService   $userCoursePaymentService
    )
    {

    }

    /**
     * @throws RegisterCourseException
     * @throws Throwable
     */
    public function run(int $userId, int $courseId, bool $isRequest, ?string $inviteCode = null): RegisterCourseResponseDto
    {
        $course = $this->courseRepository->findOrFail($courseId);

        $userCourse = $this->userCourseRepository->findUserCourseByCourseId($userId, $courseId);

        if (isset($userCourse)) {
            if ($userCourse->hasRegistered()) {
                throw RegisterCourseException::whenUserAlreadyRegistered();
            }

            if ($userCourse->hasRequested() && $isRequest) {
                throw RegisterCourseException::whenUserAlreadyRequested();
            }
        }

        return $this->userCourseRepository->transactional(function () use ($userId, $course, $inviteCode, $isRequest) {
            if ((!$isRequest) && $course->requires_invite_code) {
                if (!$this->isValidInviteCode($course, $inviteCode)) {
                    throw RegisterCourseException::whenInviteCodeIsInvalid();
                }

                $this->courseInviteCodeRepository->decreaseCapacityByCode($inviteCode);
                $this->courseInviteCodeRepository->increaseUsedByCode($inviteCode);
            }

            $userCourse = $this->userCourseRepository->registerCourse($userId, $course->id, $isRequest);

            $registerCourseResponseDto = RegisterCourseResponseDto::make($userCourse);

            if ($course->is_free || $course->requires_invite_code) {
                return $registerCourseResponseDto;
            }

            $payUrl = $this->userCoursePaymentService
                ->purchase(PurchaseRequestDto::make($userCourse->request_code, $userCourse->user->mobile));

            $registerCourseResponseDto->setPayUrl($payUrl);

            return $registerCourseResponseDto;
        });
    }

    private function isValidInviteCode(Model|Course $course, string $inviteCode): bool
    {
        $courseInviteCode = $this->courseInviteCodeRepository->findByCode($inviteCode);

        return (
            is_not_null($courseInviteCode) &&
            $courseInviteCode->course_id === $course->id &&
            $courseInviteCode->hasCapacity()
        );
    }
}
