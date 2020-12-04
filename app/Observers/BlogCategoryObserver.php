<?php

namespace App\Observers;

use App\Models\BlogCategory;

class BlogCategoryObserver
{
    /**
     * отработка перед созданием
     *
     * @param BlogCategory $blogCategory
     */
    public function creating(BlogCategory $blogCategory)
    {
        $this->setSlug($blogCategory);
    }

    /**
     * отработка перед обновлением
     *
     * @param BlogCategory $blogCategory
     */
    public function updating(BlogCategory $blogCategory)
    {

        $this->setSlug($blogCategory);
    }

    /**
     * Если поле слаг пустое, то заполняем его конвертацией заголовка
     *
     * @param BlogCategory $blogCategory
     */
    protected function setSlug(BlogCategory $blogCategory)
    {
        if ( empty( $blogCategory->slug )  )
        {
            $blogCategory->slug = \Str::slug($blogCategory->title);
        }
    }

    /**
     * Handle the blog category "created" event.
     *
     * @param  \App\Models\BlogCategory  $blogCategory
     * @return void
     */
    public function created(BlogCategory $blogCategory)
    {
        //
    }

    /**
     * Handle the blog category "updated" event.
     *
     * @param  \App\Models\BlogCategory  $blogCategory
     * @return void
     */
    public function updated(BlogCategory $blogCategory)
    {
        //
    }

    /**
     * Handle the blog category "deleted" event.
     *
     * @param  \App\Models\BlogCategory  $blogCategory
     * @return void
     */
    public function deleted(BlogCategory $blogCategory)
    {
        //
    }

    /**
     * Handle the blog category "restored" event.
     *
     * @param  \App\Models\BlogCategory  $blogCategory
     * @return void
     */
    public function restored(BlogCategory $blogCategory)
    {
        //
    }

    /**
     * Handle the blog category "force deleted" event.
     *
     * @param  \App\Models\BlogCategory  $blogCategory
     * @return void
     */
    public function forceDeleted(BlogCategory $blogCategory)
    {
        //
    }
}
