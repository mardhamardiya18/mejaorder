<?php

namespace App\Filament\Traits;

use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

trait HasParentResource
{
    public Model|Int|String|null $parent = null;

    public function bootHasParentResource(): void
    {
        if ($parent = (request()->route('parent') ?? request()->input('parent'))) {
            $parentResource = $this->getParentResource();

            $this->parent = $parentResource::resolveRecordRouteBinding($parent);

            if (! $this->parent) {
                throw new ModelNotFoundException();
            }
        }
    }

    public static function getParentResource(): string
    {
        $parentResource = static::getResource()::$parentResource;

        if (!isset($parentResource)) {
            throw new Exception('Parent resource not defined.');
        }

        return $parentResource;
    }

    protected function applyFilterToTableQuery(Builder $query)
    {
        $query = parent::applyFilterToTableQuery($query);

        return $query->where($this->getParentRelationshipKey(), $this->parent->getKey());
    }

    public function getParentRelationshipKey(): string
    {
        return $this->parent?->getForeignKey();
    }

    public function getChildPagenamePrefix(): string
    {
        return $this->pageNamePrefix ?? (string) str(static::getResource()::getSlug())->replace('/', '.')->afterLast('.');
    }

    public function getBreadcrumbs(): array
    {
        $resource = static::getResource();
        $parentResource = static::getParentResource();

        $breadcrumbs = [
            $parentResource::getUrl() => $parentResource::getBreadcrumb(),
            $parentResource::getRecordTitle($this->parent),
            $parentResource::getUrl(name: $this->getChildPagenamePrefix() . '.index'),
        ];

        if (isset($this->record)) {
            $breadcrumbs[] = $resource::getRecordTitle($this->record);
        }

        $breadcrumbs[] = $this->getBreadCrumb();

        return $breadcrumbs;
    }
}
