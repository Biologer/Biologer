@extends('layouts.contributor')

@section('content')
    <div class="container">
        <section class="section">
            <h1 class="is-size-4">Field Observations</h1>

            <div class="box">
                <nz-field-observations-table
                    list-route="api.my.field-observations.index"
                    edit-route="contributor.field-observations.edit"
                    delete-route="api.field-observations.destroy">
                </nz-field-observations-table>
            </div>
        </section>
    </div>
@endsection
