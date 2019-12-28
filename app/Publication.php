<?php

namespace App;

use App\Concerns\CanMemoize;
use App\Filters\Filterable;
use Illuminate\Support\Str;

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
     * Filter definitions.
     *
     * @return array
     */
    protected function filters()
    {
        return [
            'sort_by' => \App\Filters\SortBy::class,
            'search' => \App\Filters\Publication\Search::class,
        ];
    }

    /**
     * List of fields that users can be sorted by.
     *
     * @return array
     */
    public static function sortableFields()
    {
        return ['id', 'title'];
    }

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
            "{$this->title}{$edition}";

        if ($this->publisher && $this->place) {
            $citation .= ". {$this->place}: {$this->publisher}";
        } elseif ($this->publisher) {
            $citation .= ". {$this->publisher}";
        } elseif ($this->place) {
            $citation .= ". {$this->place}";
        }

        if ($this->page_count) {
            $citation .= ". {$this->page_count}";
        }

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
            $this->name, $this->issue,
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
        return $this->formatNamesForCitation('authors');
    }

    /**
     * Format editors for citation.
     *
     * @return string
     */
    protected function formatEditorsForCitation()
    {
        $editors = $this->formatNamesForCitation('editors');

        return $this->editors->count() === 1
            ? "{$editors} (Ed.)"
            : "{$editors} (Eds.)";
    }

    /**
     * Format names for citation.
     *
     * @param  string  $type
     * @return string
     */
    protected function formatNamesForCitation($type)
    {
        if ($this->{$type}->count() >= 6) {
            return $this->shortenName($this->{$type}->first()).' et al.';
        }

        $shortenedNames = $this->{$type}->map(function ($name) {
            return $this->shortenName($name);
        });

        $duplicates = $shortenedNames->duplicates();

        if ($duplicates->isEmpty()) {
            return $shortenedNames->join(', ', ' & ');
        }

        return $this->{$type}->map(function ($name) use ($duplicates) {
            $shortened = $this->shortenName($name);

            if ($duplicates->contains($shortened)) {
                return sprintf('%s %s', $name['last_name'], $name['first_name']);
            }

            return $shortened;
        })->join(', ', ' & ');
    }

    /**
     * Shorten the name for citation.
     *
     * @param  array  $name
     * @return string
     */
    protected function shortenName(array $name)
    {
        $lastName = ucfirst($name['last_name']);

        $firstName = implode('', array_map(function ($part) {
            return sprintf('%s.', mb_strtoupper(mb_substr($part, 0, 1)));
        }, explode(' ', $name['first_name'])));

        return sprintf('%s %s', $lastName, $firstName);
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
