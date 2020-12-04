<?php

namespace App\Repositories;

use App\Models\BlogPost as Model;
use App\Repositories\CoreRepository;

/**
 * Class BlogPostRepository
 *
 * @package App\Repositories
 */

class BlogPostRepository extends CoreRepository
{
    /**
     * @return string
     */
    protected function getModelClass()
    {
        return Model::class;
    }

    /**
     * Получить категории для вывода пагинатором
     * @param int|null $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getAllWithPaginate($perPage=null)
    {
        $columns = [
            'id',
            'title',
            'slug',
            'is_published',
            'published_at',
            'user_id',
            'category_id'
        ];

        $result = $this
            ->startConditions()
            ->select($columns)
            ->orderBy('id', 'DESC')
            //->with(['category', 'user']) // подгрузит все поля
            ->with([
                // можно так
                'category' => function($query){
                    $query->select(['id', 'title']);
                },
                // или так
                'user:id,name'
            ])
            ->paginate($perPage);
        return $result;
    }

    /**
     * Получить модель для редактирования в админке
     *
     * @param $id
     *
     * @return Model
     */
    public function getEdit($id)
    {
        return $this->startConditions()->find($id);
    }

    /**
     * Получить удаленную запись для восстановления в админке
     *
     * @param $id
     *
     * @return Model
     */
    public function getTreashedPost($id)
    {
        $result = $this->startConditions()->withTrashed()->find($id);
        return $result;
    }
}
