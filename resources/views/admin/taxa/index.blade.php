@extends('layouts.admin')

@section('content')
    <div class="container">
        <section class="section">
            <div class="level">
                <h1 class="is-size-4">Taxa</h1>
                <a href="{{ route('admin.taxa.create') }}" class="button is-primary">&nbsp;New</a>
            </div>

            <div class="box">
                <nz-taxa-table
                    list-route="api.taxa.index"
                    edit-route="admin.taxa.edit"
                    delete-route="api.taxa.destroy"
                    :categories="{{ json_encode(\App\Taxon::getCategoryOptions()) }}">
                </nz-taxa-table>
            </div>
        </section>
    </div>
@endsection
