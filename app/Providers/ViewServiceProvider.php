<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Spatie\Menu\Laravel\Facades\Menu;
use Illuminate\Support\ServiceProvider;

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
            return auth()->user()->hasAnyRole(array_wrap($roles));
        });

        Blade::if('roles', function ($roles) {
            return auth()->user()->hasAllRole(array_wrap($roles));
        });

        $this->buildSidebarMenu();
    }

    /**
     * Build sidebar menu using Spatie Menu.
     *
     * @return \Spatie\Menu\Laravel\Menu
     */
    protected function buildSidebarMenu()
    {
        return Menu::macro('sidebar', function () {
            return Menu::new()
                ->setWrapperTag('aside')
                ->withoutParentTag()
                ->addClass('menu')
                ->add(
                    Menu::new()
                        ->prepend('<p class="menu-label">'.__('navigation.my').'</p>')
                        ->addClass('menu-list')
                        ->route('contributor.field-observations.index', __('navigation.field_observations'))
                        ->setActiveClass('is-active')
                        ->setActiveClassOnLink()
                        ->setActiveFromRequest()
                )->addIf(
                    optional(auth()->user())->hasAnyRole(['admin', 'curator']),
                    Menu::new()
                        ->prepend('<p class="menu-label">'.__('navigation.curator').'</p>')
                        ->addClass('menu-list')
                        ->routeIfCan(
                            ['list', \App\FieldObservation::class],
                            'curator.pending-observations.index',
                            __('navigation.pending_observations')
                        )->routeIfCan(
                            ['list', \App\FieldObservation::class],
                            'curator.approved-observations.index',
                            __('navigation.approved_observations')
                        )->routeIfCan(
                            ['list', \App\FieldObservation::class],
                            'curator.unidentifiable-observations.index',
                            __('navigation.unidentifiable_observations')
                        )->setActiveClass('is-active')
                        ->setActiveClassOnLink()
                        ->setActiveFromRequest()
                )->addIf(
                    optional(auth()->user())->hasAnyRole(['admin', 'curator']),
                    Menu::new()
                        ->prepend('<p class="menu-label">'.__('navigation.admin').'</p>')
                        ->addClass('menu-list')
                        ->routeIf(
                            auth()->user()->hasRole('admin'),
                            'admin.field-observations.index',
                            __('navigation.all_field_observations')
                        )->routeIfCan(
                            ['list', \App\Taxon::class],
                            'admin.taxa.index',
                            __('navigation.taxa')
                        )->routeIfCan(
                            ['list', \App\User::class],
                            'admin.users.index',
                            __('navigation.users')
                        )->routeIf(
                            auth()->user()->hasRole('admin'),
                            'admin.view-groups.index',
                            __('navigation.view_groups')
                        )->setActiveClass('is-active')
                        ->setActiveClassOnLink()
                        ->setActiveFromRequest()
                );
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
