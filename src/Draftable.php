<?php

namespace Optix\Draftable;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

trait Draftable
{
    public static function bootDraftable()
    {
        static::addGlobalScope('published', function (Builder $query) {
            $query->where('published_at', '<=', Carbon::now())
                  ->whereNotNull('published_at');
        });
    }

    public function scopeWithDrafts(Builder $query)
    {
        $query->withoutGlobalScope('published');
    }

    public function scopeOnlyDrafts(Builder $query)
    {
        $query->withDrafts()
              ->where('published_at', '>', Carbon::now())
              ->orWhereNull('published_at');
    }

    public function publish()
    {
        $this->update([
            'published_at' => Carbon::now()
        ]);
    }

    public function draft()
    {
        $this->update([
            'published_at' => null
        ]);
    }
}
