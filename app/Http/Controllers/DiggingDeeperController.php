<?php

namespace App\Http\Controllers;

use App\Jobs\GeneralCatalog\GenerateCatalogMainJob;
use App\Jobs\ProccessVideoJob;
use App\Models\BlogPost;
use Carbon\Carbon;

class DiggingDeeperController extends Controller
{
    /**
     * Базовая информация:
     * @url https://laravel.com/docs/5.8/collections
     *
     * Справочная информация:
     * @url https://laravel.com/docs/api/5.8/Illuminate/Support/Collections.html
     *
     * Вариант коллекции для моделей eloquent:
     * @url https://laravel.com/docs/api/5.8/Illuminate/Database/Eloquent/Collections.html
     *
     * Билдер запросов - то, с чем можно перепутать коллекции:
     * @url https://laravel.com/docs/5.8/queries
     */
    public function collections()
    {
        $result = [];

        /**
         * @var \Illuminate\Database\Eloquent\Collection $eloquentCollection
         */
        $eloquentCollection = BlogPost::withTrashed()->get();

//        dd(__METHOD__, $eloquentCollection, $eloquentCollection->toArray());

        /**
         * @var \Illuminate\Support\Collection $collection
         */
        $collection = collect($eloquentCollection->toArray());
//        dd(
//            __METHOD__,
//            get_class($eloquentCollection),
//            get_class($collection),
//            $collection
//        );

//        $result['first'] = $collection->first();
//        $result['last'] = $collection->last();


        $result['where']['data'] = $collection
            ->where('category_id', 10)
            ->values()
            ->keyBy('id');

//        $result['where']['count'] = $result['where']['data']->count();
//        $result['where']['isEmpty'] = $result['where']['data']->isEmpty();
//        $result['where']['isNotEmpty'] = $result['where']['data']->isNotEmpty();

        //не очень красиво
//        if ($result['where']['count']){}

        //так лучше
//        if($result['where']['data']->isNotEmpty()){}

//        $result['where_first'] = $collection
//            ->firstWhere('created_at', '>', '2020-10-01 00:00:00');

        //базовая переменная не изменится, просто вернется измененная версия
//        $result['map']['all'] = $collection->map(function (array $item){
////            dd($item);
//            $newItem = new \stdClass();
//            $newItem->item_id = $item['id'];
//            $newItem->item_name = $item['title'];
//            $newItem->exists = is_null($item['deleted_at']);
//
//            return $newItem;
//        });
//
//        $result['map']['not_exists'] = $result['map']['all']
//            ->where('exists', '=', false)
//            ->values()
//            ->keyBy('item_id');



        //базовая переменная изменится (трансформируется)
        $collection->transform(function (array $item){
            $newItem = new \stdClass();
            $newItem->item_id = $item['id'];
            $newItem->item_name = $item['title'];
            $newItem->exists = is_null($item['deleted_at']);
            $newItem->created_at = Carbon::parse($item['created_at']);

            return $newItem;
        });
//        dd($collection);
//        $newItem = new \stdClass();
//        $newItem->id = 9999;
//
//        $newItem2 = new \stdClass();
//        $newItem2->id = 8888;
//        dd($newItem, $newItem2);
//
//        $collection->prepend($newItem)
//        $collection->push($newItem2)
//        dd($collection, $newItem, $newItem2);

            //Установить элемент в начало и конец коллекции
//        $newItemFirst = $collection->prepend($newItem)->first();
//        $newItemLast = $collection->push($newItem2)->last();
//        $pulledItems = $collection->pull(1);
//        dd(compact('collection', 'newItemFirst', 'newItemLast', 'pulledItems'));

        //Фильтрация. Замена orWhere()
//        $filtered = $collection->filter(function ($item){
//            $byDay = $item->created_at->isFriday();
//            $byDate = $item->created_at->day == 11;
//
////            $result = $item->created_at->isFriday() && ($item->created_at->day == 11);
//            $result = $byDay && $byDate;
//
//            return $result;
//        });
//        dd(compact('filtered'));

//        $sortedSimpleCollection = collect([5,3,1,2,4])->sort()->values();
//        $sortedAscCollection = $collection->sortBy('created_at');
//        $sortedDescCollection = $collection->sortByDesc('item_id');
//
//        dd(compact('sortedSimpleCollection', 'sortedAscCollection', 'sortedDescCollection'));
    }

    public function processVideo()
    {
        ProccessVideoJob::dispatch()
        //Отсрочка выполнения задания от момента помещения в очередь.
        // Не плияет на паузу между попытками выполнить задачу
        //->delay(10)
        //->onQueue('name_of_queue')
        ;
    }

    /**
     * @link http://lara.loc/digging_deeper/prepare-catalog
     *
     * php artisan queue:listen --queue=generate-catalog --tries=3 --delay=10
     */
    public function prepareCatalog()
    {
        GenerateCatalogMainJob::dispatch();
    }
}
