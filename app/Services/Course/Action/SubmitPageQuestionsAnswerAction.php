<?php

namespace App\Services\Course\Action;


use App\Contracts\HasPageInterface;
use App\Enums\PageTypeEnum;
use App\Enums\UserItemsStatusEnum;
use App\Models\Course;
use App\Models\Question;
use App\Models\SubSyllabus;
use App\Repositories\ItemRepository;
use App\Repositories\PageRepository;
use App\Repositories\QuestionAnswerRepository;
use App\Repositories\QuestionRepository;
use App\Repositories\UserCourseRepository;
use App\Repositories\UserPageRepository;
use App\Services\Course\AnswerQuestionAction;
use App\Services\Course\Dto\SubmitQuestionAnswersDTO;
use App\Services\Course\Exceptions\PreviousQuestionsNotAnsweredException;
use App\Services\Course\Exceptions\UserDoesNotRegisteredCourseException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Throwable;

class SubmitPageQuestionsAnswerAction
{
    public function __construct(
        private readonly QuestionRepository         $questionRepository,
        private readonly UserCourseRepository       $userCourseRepository,
        private readonly QuestionAnswerRepository   $questionAnswerRepository,
        private readonly AnswerQuestionAction       $answerQuestionAction,
        private readonly ItemRepository             $itemRepository,
        private readonly UpdateCourseProgressAction $updateCourseProgressAction,
        private readonly PageRepository             $pageRepository,
        private readonly UserPageRepository         $userPageRepository,
    )
    {
    }

    /**
     * @throws Throwable
     * @throws UserDoesNotRegisteredCourseException
     */
    public function run(SubmitQuestionAnswersDTO $submitQuestionAnswersDTO)
    {
        $usable = $this->resolveUsableModel($submitQuestionAnswersDTO->getUsable())
            ->findOrFail($submitQuestionAnswersDTO->getUsableId());

        $courseId = $this->getCourseId($usable);

        $this->checkUserHasRegistered($courseId, $submitQuestionAnswersDTO->getUserId());

        $this->checkPreviousPageHasBeenCompleted(
            $usable,
            $submitQuestionAnswersDTO->getPageNumber(),
            $submitQuestionAnswersDTO->getUserId()
        );

        $itemsIdentifiers = collect($submitQuestionAnswersDTO->getItems())
            ->pluck('identifier')
            ->toArray();

        $items = $this->itemRepository->getQuestionItemsByIdentifiers($itemsIdentifiers);

        // todo: answer page question!!

//        if (count($itemsIdentifiers) !== count($items)) {
//            throw new QuestionsNotCompletelyAnsweredException();
//        }

        $this->questionRepository->transactional(function () use ($items, $usable, $submitQuestionAnswersDTO) {
            $userId = $submitQuestionAnswersDTO->getUserId();
            $answers = $submitQuestionAnswersDTO->getItems();

            foreach ($items as $item) {
                $question = $item->content;

                if (!$this->isValidToAnswer($question, $userId)) {
                    return;
                }

                //todo??
                $submittedAnswer = collect($answers)
                    ->where('identifier', $item->identifier)
                    ->first()['answers'];

                $this->answerQuestionAction->run($userId, $question, $submittedAnswer, true);
            }


            $this->updateCourseProgressAction->run(
                $usable,
                $submitQuestionAnswersDTO->getPageNumber(),
                $userId
            );
        });
    }

    /**
     * @throws PreviousQuestionsNotAnsweredException
     */
    private function checkPreviousPageHasBeenCompleted($usable, int $pageNumber, int $userId): void
    {
        if ($pageNumber <= 1) {
            return;
        }

        $pages = $this->pageRepository->getPages($usable->getMorphClass(), $usable->id, PageTypeEnum::CONTENT());

        $previousPage = (clone $pages)
            ->where('order', $pageNumber - 1)
            ->first();

        $userPage = $this->userPageRepository->findByUserAndPage($userId, $previousPage->id);

        if (is_null($userPage) || ($userPage->status !== UserItemsStatusEnum::COMPLETED())) {
            throw new PreviousQuestionsNotAnsweredException();
        }

        $nextPage = (clone $pages)
            ->where('order', $pageNumber + 1)
            ->first();

        if (is_null($nextPage)) {
            $usablePageIds = $pages->pluck('id')->toArray();

            $userPages = $this->userPageRepository->getByPageIdsForUser($userId, $usablePageIds);
            $completedPagesCount = (clone $userPages)
                ->where('status', UserItemsStatusEnum::COMPLETED())
                ->count();

            if (!in_array($completedPagesCount, [count($usablePageIds), (count($usablePageIds) - 1)])) {
                throw new PreviousQuestionsNotAnsweredException();
            }
        }
    }

    private function resolveUsableModel(string $usable): Model&HasPageInterface
    {
        return app(Relation::getMorphedModel($usable));
    }

    private function getCourseId(Model&HasPageInterface $usable): int
    {
        if ($usable instanceof Course) {
            return $usable->id;
        }


        if ($usable instanceof SubSyllabus) {
            return $usable->usable->usable->id;
        }
    }

    /**
     * @throws UserDoesNotRegisteredCourseException|Throwable
     */
    private function checkUserHasRegistered(int $courseId, int $userId): void
    {
        throw_if(
            !$this->userCourseRepository->hasUserRegistered($courseId, $userId),
            UserDoesNotRegisteredCourseException::class
        );
    }

    private function isValidToAnswer(Question $question, int $userId): bool
    {
        $answer = $this->questionAnswerRepository->findUserQuestionAnswer($question->id, $userId);


        return !($answer && (!$question->is_mutable));
    }
}
