<?php

namespace App;

class PublicationType extends \MyCLabs\Enum\Enum
{
    const BOOK = 'book';
    const BOOK_CHAPTER = 'book_chapter';
    const PAPER = 'paper';
    const SYMPOSIUM = 'symposium';
    const THESIS = 'thesis';

    /**
     * @return array
     */
    public static function darwinCore()
    {
        return [
            self::BOOK => 'book',
            self::BOOK_CHAPTER => 'chapter',
            self::PAPER => 'journal',
            self::SYMPOSIUM => 'conference',
            self::THESIS => 'thesis',
        ];
    }

    /**
     * Get value for Darwin Core.
     *
     * @return string
     */
    public function toDarwinCore()
    {
        return self::darwinCore()[$this->value];
    }

    /**
     * Types that have `name` attribute.
     *
     * @return array
     */
    public static function hasName()
    {
        return [
            self::BOOK_CHAPTER,
            self::SYMPOSIUM,
            self::PAPER,
        ];
    }

    /**
     * Types that have `publisher` attribute.
     *
     * @return array
     */
    public static function hasPublisher()
    {
        return [
            self::BOOK,
            self::BOOK_CHAPTER,
            self::PAPER,
        ];
    }

    /**
     * Types that require `publisher` attribute.
     *
     * @return array
     */
    public static function requiresPublisher()
    {
        return [
            self::BOOK,
            self::BOOK_CHAPTER,
        ];
    }

    /**
     * Types that have `editors` attribute.
     *
     * @return array
     */
    public static function hasEditors()
    {
        return [
            self::BOOK,
            self::BOOK_CHAPTER,
            self::PAPER,
        ];
    }

    /**
     * Types that require `editors` attribute.
     *
     * @return array
     */
    public static function requiresEditors()
    {
        return [
            self::BOOK,
            self::BOOK_CHAPTER,
        ];
    }

    /**
     * Types that have `issue` attribute.
     *
     * @return array
     */
    public static function hasIssue()
    {
        return [
            self::BOOK,
            self::BOOK_CHAPTER,
            self::PAPER,
        ];
    }

    /**
     * Types that require `issue` attribute.
     *
     * @return array
     */
    public static function requiresIssue()
    {
        return [
            self::PAPER,
        ];
    }

    /**
     * Types that have `place` attribute.
     *
     * @return array
     */
    public static function hasPlace()
    {
        return [
            self::BOOK,
            self::BOOK_CHAPTER,
            self::PAPER,
            self::THESIS,
        ];
    }

    /**
     * Types that require `place` attribute.
     *
     * @return array
     */
    public static function requiresPlace()
    {
        return [
            self::BOOK,
            self::BOOK_CHAPTER,
        ];
    }

    /**
     * Types that have `page_range` attribute.
     *
     * @return array
     */
    public static function hasPageRange()
    {
        return [
            self::BOOK_CHAPTER,
            self::PAPER,
            self::SYMPOSIUM,
        ];
    }

    /**
     * Options for publication type.
     *
     * @return array
     */
    public static function options()
    {
        return collect(self::toArray())->map(function ($type) {
            return [
                'value' => $type,
                'label' => trans('publicationTypes.'.$type),
                'has_name' => in_array($type, static::hasName()),
                'has_editors' => in_array($type, static::hasEditors()),
                'requires_editors' => in_array($type, static::requiresEditors()),
                'has_publisher' => in_array($type, static::hasPublisher()),
                'requires_publisher' => in_array($type, static::requiresPublisher()),
                'has_issue' => in_array($type, static::hasIssue()),
                'requires_issue' => in_array($type, static::requiresIssue()),
                'has_place' => in_array($type, static::hasPlace()),
                'requires_place' => in_array($type, static::requiresPlace()),
                'has_page_range' => in_array($type, static::hasPageRange()),
            ];
        })->values();
    }
}
