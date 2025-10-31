<?php

namespace App\Http\Client\Api\Controllers\My;

use App\Http\Client\Api\Controllers\Controller;
use App\Http\Client\Api\Resources\UsertrackerResource;
use App\Http\Client\Requests\UsertrackerRequest;
use App\Models\Item;
use App\Models\User;
use App\Models\Usertracker;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class UsertrackerController extends Controller
{
    /**
     * @api {get} /api/my/tracks/form 01. Форма створення: Емоції
     * @apiVersion 1.0.0
     * @apiName TrackerForm
     * @apiGroup Tracker
     *
     * @apiSuccessExample Success-Response:
     * HTTP/1.1 200 OK
     * "form": {
     *     "emotions": [
     *         {
     *             "key": "sum",
     *             "name": "Сум",
     *             "color": "#006516",
     *             "id": "9eb03471-d199-4948-95ab-def7d58336a2"
     *         },
     *         {
     *             "key": "zlist",
     *             "name": "Злість",
     *             "color": "#FFFFFF",
     *             "id": "9eb03471-d989-43f0-a2e4-1d4e4ecb60a8"
     *         },
     *     ]
     * },
     *
     */
    public function form(Request $request)
    {
        $additional = [
            'form' => [
                'emotions' => Item::getList(Item::TYPE_EMOTIONS),
            ]
        ];

        return $additional;
    }

    /**
     * @api {post} /api/my/tracks 02. Добавити свій настрій
     * @apiVersion 1.0.0
     * @apiName TrackerTrack
     * @apiGroup Tracker
     *
     * @apiDescription Настрій на поточний день (останній настрій)
     *
     * @apiParam {Array} emotions Емоції
     * @apiParam {Integer=1-5} mood Настрій, 1 - Зле, 5 - Чудово все
     * @apiParam {Integer=1-10} anxiety Тривожність, 1 - немає тривоги, 10 - капець як тривожно
     * @apiParam {String} [comment] Довільний коментар
     *
     * @apiParamExample {json} Request-Example:
     * {
     *      "emotions": [{id:123},{id:456},{id:678}],
     *      "mood": 5,
     *      "anxiety": 2,
     *      "comment": "Був чудовий день, їздив на велосипеді",
     * }
     *
     */
    public function tracks(UsertrackerRequest $request)
    {
        /** @var User $user */
        $user = $request->user();

        $data = $request->getData();

        $usertracker = $user->usertracks()->create(['data' => $data]);

        if ($emotionIds = $request->input('emotions', [])) {
            $usertracker->emotions()->sync(Arr::pluck($emotionIds, 'id'));
        }

        return response()->json(['message' => 'Дані успішно збережено!', 'id' => $usertracker->id]);
    }

    /**
     * @api {get} /api/my/tracks/{2025-06-02}/list 04. Список настроїв за Датою
     * @apiVersion 1.0.0
     * @apiName TrackerList
     * @apiGroup Tracker
     *
     * @apiSuccessExample Success-Response:
     * HTTP/1.1 200 OK
     * "data": [
     *     {
     *         "id": "9f06918a-fee8-4333-b794-bc838df8c753",
     *         "mood": 4,
     *         "anxiety": 3,
     *         "comment": "Все нормально",
     *         "created_at": "2025-05-29T08:00:04.000000Z",
     *         "emotions": [
     *             {
     *                 "key": "cikavist",
     *                 "name": "Цікавість",
     *                 "color": "#650043",
     *                 "id": "9eb03471-ff5c-477a-872f-2e97464b135e"
     *             },
     *             {
     *                 "key": "zdivuvannya",
     *                 "name": "Здивування",
     *                 "color": "#650043",
     *                 "id": "9eb03472-0727-45a7-b806-c53286c9355f"
     *             }
     *         ]
     *     },
     *     {
     *         "id": "9f069253-eda4-4f94-b9df-baf019d4b548",
     *         "mood": 4,
     *         "anxiety": "",
     *         "comment": "",
     *         "created_at": "2025-05-29T08:02:15.000000Z",
     *         "emotions": []
     *     }
     * ],
     *
     */
    public function list(Request $request, string $fixed_at)
    {
        $date = Carbon::parse($fixed_at)->format('Y-m-d');

        $trackers = $request->user()->usertracks()->whereDate('fixed_at', $date)->latest()->get();

        return UsertrackerResource::collection($trackers);
    }

    /**
     * @api {get} /api/my/tracks/statistic 05. Статистика (аналітика)
     * @apiExample {url} Example usage:
     *       /api/my/tracks/statistic?created_at_from=2025-05-01&created_at_to=2025-05-31
     * @apiVersion 1.0.0
     * @apiName TrackerStatistic
     * @apiGroup Tracker
     *
     * @apiParam {Date} [created_at_from=початок_місяця] Дата статистики, від (початку місяця)
     * @apiParam {Date} [created_at_to=сьогодні] Дата статистики, по (сьогодні)
     *
     * @apiSuccessExample Success-Response:
     * HTTP/1.1 200 OK
     * "days": 2,
     * "tracks": [
     *     {
     *         "id": "9f069253-eda4-4f94-b9df-baf019d4b548",
     *         "mood": 4,
     *         "anxiety": null,
     *         "comment": "",
     *         "created_at": "2025-05-29T08:02:15.000000Z",
     *         "emotions": []
     *     },
     *     {
     *         "id": "9f052e42-e033-4a45-a7d2-7a12c16311c9",
     *         "mood": 5,
     *         "anxiety": 1,
     *         "comment": "Був чудовий день, їздив на велосипеді 7 раз",
     *         "created_at": "2025-05-28T15:26:37.000000Z",
     *         "emotions": [
     *             {
     *                 "key": "sum",
     *                 "name": "Сум",
     *                 "color": "#006516",
     *                 "id": "9eb03471-d199-4948-95ab-def7d58336a2"
     *             },
     *             {
     *                 "key": "zdivuvannya",
     *                 "name": "Здивування",
     *                 "color": "#650043",
     *                 "id": "9eb03472-0727-45a7-b806-c53286c9355f"
     *             }
     *         ]
     *     }
     * ],
     * "emotions": [
     *     {
     *         "id": "9eb03472-0727-45a7-b806-c53286c9355f",
     *         "name": "Здивування",
     *         "color": "#650043",
     *         "count": 1
     *     },
     *     {
     *         "id": "9eb03471-d989-43f0-a2e4-1d4e4ecb60a8",
     *         "name": "Злість",
     *         "color": "#FFFFFF",
     *         "count": 0
     *     },
     * ],
     *
     */
    public function statistic(Request $request)
    {
        $user = $request->user();

        $from = $request->input('created_at_from', now()->startOfMonth()->toDateString());
        $to = $request->input('created_at_to',   now()->toDateString());

        $sub = $user->usertracks()
            ->selectRaw('fixed_at, MAX(created_at) AS max_created_at')
            ->whereBetween('fixed_at', [$from, $to])
            ->groupBy('fixed_at');

        $tracks = Usertracker::joinSub($sub, 'latest', function($join) {
            $join->on('usertrackers.fixed_at',       '=', 'latest.fixed_at')
                ->on('usertrackers.created_at',     '=', 'latest.max_created_at');
        })
            ->where('usertrackers.user_id', $user->id)
            ->with('emotions')
            ->orderByDesc('usertrackers.fixed_at')
            ->get();

        return [
            // рахуємо к-сть безперервних fixed_at записів (днів) в Usertracker починаючи від учора і до першого пропуску, плюс додаємо 1 якщо сьогодні теж відмітили.
            'days' => $user->getTrackedStreak(),

            // настрій, емоції, тривожність, коментар по дням https://i.imgur.com/8kXP6Lq.png
            'tracks' => UsertrackerResource::collection($tracks),

            // два цикла - по Item::getList(Item::TYPE_EMOTIONS) і в середині по $tracks
            // діаграма емоцій та гісторгама https://i.imgur.com/HJjpLjD.png https://i.imgur.com/EMzmRDA.png
            'emotions' => $this->getEmotionsCount($tracks),
        ];
    }

    /**
     * Обраховує кількість емоцій кожного типу для користувача
     *
     * @param \Illuminate\Support\Collection|Usertracker[] $tracks Колекція треків з eager-loaded 'emotions'
     * @return array
     */
    private function getEmotionsCount($tracks): array
    {
        $emotions = Item::getList(Item::TYPE_EMOTIONS);

        $countsById = $tracks
            ->flatMap->emotions
            ->countBy('id');

        return collect($emotions)
            ->map(function ($emotion) use ($countsById) {
                return [
                    'id'    => $emotion['id'],
                    'name'  => $emotion['name'],
                    'color' => $emotion['color'],
                    'count' => $countsById->get($emotion['id'], 0),
                ];
            })
            ->sortByDesc('count')
            ->values()
            ->toArray();
    }
}
