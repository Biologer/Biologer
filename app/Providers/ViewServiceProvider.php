<?php

namespace App\Providers;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Blade;
use Spatie\Menu\Laravel\Facades\Menu;
use Illuminate\Support\ServiceProvider;
use App\Http\ViewComposers\DashboardComposer;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::if('role', function ($roles) {
            return auth()->user()->hasAnyRole(Arr::wrap($roles));
        });

        Blade::if('roles', function ($roles) {
            return auth()->user()->hasAllRole(Arr::wrap($roles));
        });

        View::composer('layouts.dashboard', DashboardComposer::class);

        $this->buildSidebarMenu();
        $this->buildPreferencesSidebar();
    }

    /**
     * Build sidebar menu using Spatie Menu.
     *
     * @return \Spatie\Menu\Laravel\Menu
     */
    private function buildSidebarMenu()
    {
        return Menu::macro('sidebar', function () {
            return Menu::new()
                ->setWrapperTag('aside')
                ->withoutParentTag()
                ->addClass('menu')
                ->add(
                    Menu::new()
                        ->prepend('<p class="menu-label">'.trans('navigation.public').'</p>')
                        ->addClass('menu-list')
                        ->route('contributor.public-field-observations.index', trans('navigation.field_observations'))
                        ->setActiveClass('is-active')
                        ->setActiveClassOnLink()
                        ->setActiveFromRequest()
                )->add(
                    Menu::new()
                        ->prepend('<p class="menu-label">'.trans('navigation.my').'</p>')
                        ->addClass('menu-list')
                        ->route('contributor.field-observations.index', trans('navigation.field_observations'))
                        ->setActiveClass('is-active')
                        ->setActiveClassOnLink()
                        ->setActiveFromRequest()
                )->addIf(
                    optional(auth()->user())->hasAnyRole(['admin', 'curator']),
                    Menu::new()
                        ->prepend('<p class="menu-label">'.trans('navigation.curator').'</p>')
                        ->addClass('menu-list')
                        ->routeIfCan(
                            ['list', \App\FieldObservation::class],
                            'curator.pending-observations.index',
                            trans('navigation.pending_observations')
                        )->routeIfCan(
                            ['list', \App\FieldObservation::class],
                            'curator.approved-observations.index',
                            trans('navigation.approved_observations')
                        )->routeIfCan(
                            ['list', \App\FieldObservation::class],
                            'curator.unidentifiable-observations.index',
                            trans('navigation.unidentifiable_observations')
                        )->setActiveClass('is-active')
                        ->setActiveClassOnLink()
                        ->setActiveFromRequest()
                )->addIf(
                    optional(auth()->user())->hasAnyRole(['admin', 'curator']),
                    Menu::new()
                        ->prepend('<p class="menu-label">'.trans('navigation.admin').'</p>')
                        ->addClass('menu-list')
                        ->routeIf(
                            auth()->user()->hasRole('admin'),
                            'admin.field-observations.index',
                            trans('navigation.all_field_observations')
                        )->route(
                            'admin.literature-observations.index',
                            trans('navigation.literature_observations')
                        )->routeIfCan(
                            ['list', \App\Taxon::class],
                            'admin.taxa.index',
                            trans('navigation.taxa')
                        )->routeIfCan(
                            ['list', \App\User::class],
                            'admin.users.index',
                            trans('navigation.users')
                        )->routeIf(
                            auth()->user()->hasRole('admin'),
                            'admin.view-groups.index',
                            trans('navigation.view_groups')
                        )->routeIfCan(
                            ['list', \App\Publication::class],
                            'admin.publications.index',
                            trans('navigation.publications')
                        )->setActiveClass('is-active')
                        ->setActiveClassOnLink()
                        ->setActiveFromRequest()
                );
        });
    }

    private function buildPreferencesSidebar()
    {
        return Menu::macro('preferencesSidebar', function () {
            return Menu::new()
                ->setWrapperTag('aside')
                ->withoutParentTag()
                ->addClass('menu')
                ->add(
                    Menu::new()
                        ->addClass('menu-list')
                        ->route('preferences.general', __('navigation.preferences.general'))
                        ->route('preferences.account', __('navigation.preferences.account'))
                        ->route('preferences.license', __('navigation.preferences.license'))
                        ->setActiveClass('is-active')
                        ->setActiveClassOnLink()
                        ->setActiveFromRequest()
                );
        });
    }
}
