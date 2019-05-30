<?php

namespace App;

use App\Filters\Filterable;
use Illuminate\Support\Str;
use App\Concerns\CanMemoize;

class Publication extends Model
{
    use CanMemoize, Filterable;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'authors' => 'collection',
        'editors' => 'collection',
        'year' => 'integer',
        'page_count' => 'integer',
        'created_by_id' => 'integer',
        'attachment_id' => 'integer',
    ];

    /**
     * Relation to the user who has added the publication.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation to the attached document that is provided with the publication.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function attachment()
    {
        return $this->belongsTo(PublicationAttachment::class);
    }

    /**
     * Format citation using data from publication, based on type.
     *
     * @return string
     */
    protected function makeDefaultCitation()
    {
        $method = 'make'.Str::studly($this->type).'Citation';

        if (method_exists($this, $method)) {
            return $this->{$method}();
        }
    }

    /**
     * Construct citation for book using data from publication.
     *
     * @return string
     */
    protected function makeBookCitation()
    {
        $edition = $this->issue ? " ({$this->issue})" : '';

        $citation = "{$this->formatAuthorForCitation()} ({$this->year}). ".
            "{$this->title}{$edition}. {$this->place}: {$this->publisher}";

        if ($this->doi) {
            $citation .= ". {$this->doi}";
        } elseif ($this->link) {
            $citation .= sprintf('. Retrieved from %s', $this->link);
        }

        return $citation;
    }

    /**
     * Construct citation for book chapter using data from publication.
     *
     * @return string
     */
    protected function makeBookChapterCitation()
    {
        $citation = "{$this->formatAuthorForCitation()} ({$this->year}). {$this->title}. In ";

        if ($this->editors->isNotEmpty()) {
            $editors = $this->formatEditorsForCitation();

            $citation .= "{$editors}. ";
        }

        $edition = '';

        if ($this->issue || $this->page_range) {
            $edition = sprintf(' (%s)', implode(', ', array_filter([$this->issue, $this->page_range])));
        }

        $citation .= "{$this->name}{$edition}. {$this->place}: {$this->publisher}";

        if ($this->doi) {
            $citation .= ". {$this->doi}";
        } elseif ($this->link) {
            $citation .= sprintf('. Retrieved from %s', $this->link);
        }

        return $citation;
    }

    /**
     * Construct citation for symposium using data from publication.
     *
     * @return string
     */
    protected function makeSymposiumCitation()
    {
        return $this->makeBookChapterCitation();
    }

    /**
     * Construct citation for paper in journal using data from publication.
     *
     * @return string
     */
    protected function makePaperCitation()
    {
        $citation = vsprintf('%s (%s). %s. %s, %s', [
            $this->formatAuthorForCitation(), $this->year, $this->title,
            $this->name, $this->issue
        ]);

        if ($this->page_range) {
            $citation .= ", {$this->page_range}.";
        }

        $citation .= $this->publisher
            ? " {$this->place}: {$this->publisher}"
            : " {$this->place}";

        if ($this->doi) {
            $citation .= ". {$this->doi}";
        } elseif ($this->link) {
            $citation .= sprintf('. Retrieved from %s', $this->link);
        }

        return $citation;
    }

    /**
     * Construct citation for thesis using data from publication.
     *
     * @return string
     */
    protected function makeThesisCitation()
    {
        return $this->makeBookCitation();
    }

    /**
     * Format authors for citation.
     *
     * @return string
     */
    protected function formatAuthorForCitation()
    {
        return $this->authors->count() >= 6
            ? $this->authors->first().' et al'
            : $this->authors->join(', ', ' & ');
    }

    /**
     * Format editors for citation.
     *
     * @return string
     */
    protected function formatEditorsForCitation()
    {
        $editors = $this->editors->count() >= 6
            ? $this->editors->first().' et al'
            : $this->editors->join(', ', ' & ');

        if ($this->editors->count() === 1) {
            return $editors.' (Ed.)';
        }

        if ($this->editors->count() > 1) {
            return $editors.' (Eds.)';
        }
    }

    /**
     * Get the instance of PublicationType.
     *
     * @return \App\PublicationType
     */
    public function type()
    {
        return $this->memoize('type', function () {
            return new PublicationType($this->type);
        });
    }

    /**
     * Delete attachment.
     *
     * @return void
     */
    public function deleteAttachment()
    {
        optional($this->attachment)->delete();
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (! $model->citation) {
                $model->citation = $model->makeDefaultCitation();
            }
        });

        static::deleting(function ($model) {
            $model->deleteAttachment();
        });
    }
}
