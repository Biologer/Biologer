@extends('layouts.admin')

@section('content')
    <div class="container">
        <section class="section">
            <div class="box">
                <nz-taxon-form inline-template
                    action="{{ route('api.taxa.store') }}">
                    <div class="">
                        <div class="columns">
                            <div class="column is-half">
                                <b-field label="Name"
                                    :type="form.errors.has('name') ? 'is-danger' : ''"
                                    :message="form.errors.has('name') ? form.errors.first('name') : ''">
                                    <b-input v-model="form.name"></b-input>
                                </b-field>
                            </div>

                            <div class="column is-half">
                                <nz-taxon-autocomplete label="Parent"
                                    v-model="parentName"
                                    @select="onTaxonSelect"
                                    :errors="form.errors">
                                </nz-taxon-autocomplete>
                            </div>
                        </div>

                        <hr>

                        <button type="button"
                            class="button is-primary"
                            :class="{
                                'is-loading': form.processing
                            }"
                            @click="submit">
                            Save
                        </button>
                        <a :href="redirect" class="button is-text">Cancel</a>
                    </div>
                </nz-taxon-form>
            </div>
        </section>
    </div>
@endsection
