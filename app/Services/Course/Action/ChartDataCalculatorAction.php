<?php

namespace App\Services\Course\Action;

use App\Models\Chart;
use App\Repositories\QuestionRepository;

class ChartDataCalculatorAction
{
    public function __construct(private QuestionRepository $questionRepository)
    {
    }

    public function run(Chart $chart, bool $useData = false): Chart
    {
        $dynamicData = $chart->dynamic_data;

        $chartData = match ($dynamicData['type']) {
            'SCORE_BASE' => $this->handleScoreBaseAnswer($dynamicData, $useData),
            'EXTENDABLE_BASE' => $this->handleExtendableBase($dynamicData)
        };

        $chart->meta_data = $chartData;

        return $chart;
    }

    private function handleScoreBaseAnswer(array $dynamicData, bool $useData = false): array
    {
        return match ($dynamicData['model']){
            'ARCHE_TYPE' => $this->calculateArcheType($dynamicData),
            'LIFE_WHEEL' => $this->calculateLifeWheel($dynamicData, $useData),
        };
    }

    private function calculateLifeWheel(array $dynamicData, bool $useData = false): array
    {
        $numberToDivision = $useData ? 1 : 50;

        return $this->scoreBaseCalculator($dynamicData, $numberToDivision);
    }

    private function calculateArcheType(array $dynamicData): array
    {

        return $this->scoreBaseCalculator($dynamicData);
    }

    private function scoreBaseCalculator(array $dynamicData, int $numberToDivision = 1): array
    {
        $questions = $this->questionRepository->getWithUserAnswersAndTagsByIds($dynamicData['question_ids']);

        $tags = $questions->pluck('tags')->flatten()->pluck('tag')->unique('key');

        $tagsScore = [];
        foreach ($questions as $question) {
            $userAnswer = $question->userAnswer->answer[0];
            $score = collect($question->options_score)->where('key', $userAnswer)->first()['score'];

            foreach ($question->tags as $tag) {

                $tagsScore[$tag->tag->key] = ($tagsScore[$tag->tag->key] ?? 0) + $score;
            }
        }

        $metaData = $tags->map(function ($tag) use ($tagsScore, $numberToDivision){
            return [
                'key' => $tag->name,
                'value' => ($numberToDivision === 1) ? $tagsScore[$tag->key] : ($tagsScore[$tag->key] / 50 * 100)
            ];
        });

        return $metaData->toArray();
    }

    private function handleExtendableBase(array $dynamicData): array
    {
        if ($dynamicData['model'] === 'MEGA_BIORHYTHM'){
            $positiveValueQuestionIds = array_merge(...$dynamicData['p_question_ids']);
            $positiveValueQuestions = $this->questionRepository->getWithUserAnswersByIds($positiveValueQuestionIds);

            $negativeValueQuestionIds = array_merge(...$dynamicData['n_question_ids']);
            $negativeValueQuestions = $this->questionRepository->getWithUserAnswersByIds($negativeValueQuestionIds);

            $result = [];
            foreach ($dynamicData['p_question_ids'] as $questionIds){
                $yearQuestion = (clone $positiveValueQuestions)->find($questionIds[0]);
                if (is_null($yearQuestion->userAnswer)){
                    continue;
                }
                $year = collect($yearQuestion->options)->where('key', $yearQuestion->userAnswer->answer[0])->first()['value'];

                $importanceQuestion = (clone $positiveValueQuestions)->find($questionIds[1]);
                $importance = collect($importanceQuestion->options)->where('key', $importanceQuestion->userAnswer->answer[0])->first()['value'];

                $result[] = [
                    'key' => $year,
                    'value' => $importance
                ];
            }

            foreach ($dynamicData['n_question_ids'] as $questionIds){
                $yearQuestion = (clone $negativeValueQuestions)->find($questionIds[0]);
                if (is_null($yearQuestion->userAnswer)){
                    continue;
                }
                $year = collect($yearQuestion->options)->where('key', $yearQuestion->userAnswer->answer[0])->first()['value'];

                $importanceQuestion = (clone $negativeValueQuestions)->find($questionIds[1]);
                $importance = collect($importanceQuestion->options)->where('key', $importanceQuestion->userAnswer->answer[0])->first()['value'];

                $result[] = [
                    'key' => $year,
                    'value' => (-1) * $importance
                ];
            }

            usort($result, function($a, $b) {
                return $a['key'] <=> $b['key'];
            });


            $finalResult = [];
            foreach ($result as $item) {
                $key = $item['key'];
                if (isset($finalResult[$key])) {
                    $finalResult[$key]['value'] = (string)((int)$finalResult[$key]['value'] + (int)$item['value']);
                } else {
                    $finalResult[$key] = [
                        "key" => $item['key'],
                        "value" => (string)$item['value']
                    ];
                }
            }

            return array_values($finalResult);
        }
    }
}
