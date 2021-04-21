<?php

namespace App\Observers;

use App\Models\Article;
use App\Models\Menu;

class ArticleObserver
{
    /**
     * Listen to the Article retrieved event.
     *
     * @param Article $article
     */
    public function retrieved(Article $article)
    {
        $this->clearCache($article);
    }

    /**
     * Listen to the Article created event.
     *
     * @param Article $article
     */
    public function created(Article $article)
    {
        $this->clearCache($article);
    }

    /**
     * Listen to the Article updated event.
     *
     * @param Article $article
     */
    public function updated(Article $article)
    {
        $this->clearCache($article);
    }

    /**
     * Listen to the Article saved event.
     *
     * @param Article $article
     */
    public function saved(Article $article)
    {
        $this->clearCache($article);
    }

    /**
     * Listen to the Article deleted event.
     *
     * @param Article $article
     */
    public function deleted(Article $article)
    {
        $this->clearCache($article);
    }

    /**
     * Listen to the Article restored event.
     *
     * @param Article $article
     */
    public function restored(Article $article)
    {
        $this->clearCache($article);
    }

    private function clearCache($article)
    {
        $article->forgetCache();
        \Artisan::call('view:clear');
    }
}
