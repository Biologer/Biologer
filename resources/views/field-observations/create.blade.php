@extends('layouts.contributor')

@section('main')
    <div class="container">
        <section class="section">
            <div class="columns">
                <div class="column is-half">
                    <b-field class="columns">
                        <b-upload class="column"
                            type="is-fullwidth is-primary"
                            drag-drop>
                            <section class="section">
                                <div class="content has-text-centered">
                                    <p>
                                        <b-icon icon="plus"
                                                size="is-large">
                                        </b-icon>
                                    </p>
                                    <p>Add image</p>
                                </div>
                            </section>
                        </b-upload>
                        <b-upload class="column"
                                  type="is-fullwidth is-primary"
                                  drag-drop>
                            <section class="section">
                                <div class="content has-text-centered">
                                    <p>
                                        <b-icon icon="plus"
                                                size="is-large">
                                        </b-icon>
                                    </p>
                                    <p>Add image</p>
                                </div>
                            </section>
                        </b-upload>
                        <b-upload class="column"
                                  type="is-fullwidth is-primary"
                                  drag-drop>
                            <section class="section">
                                <div class="content has-text-centered">
                                    <p>
                                        <b-icon icon="plus"
                                                size="is-large">
                                        </b-icon>
                                    </p>
                                    <p>Add image</p>
                                </div>
                            </section>
                        </b-upload>
                    </b-field>
                    <nz-date-input message="Message" type="is-danger"></nz-date-input>
                    <nz-taxon-autocomplete></nz-taxon-autocomplete>
                </div>
                <div class="column is-half">

                </div>
            </div>
        </section>
    </div>
@endsection
