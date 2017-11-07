@extends('layouts.admin')

@section('content')
    <div class="container">
        <section class="section">
            <h1 class="is-size-4">Field Observations</h1>

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
