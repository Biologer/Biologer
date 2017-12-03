@extends('layouts.dashboard', ['title' => 'Edit Taxon'])

@section('content')
    <div class="box">
        <nz-taxon-form inline-template
            action="{{ route('api.taxa.update', $taxon) }}"
            method="put"
            :taxon="{{ $taxon }}"
            :rank="{{ json_encode(App\Taxon::getRankOptions()) }}">
            <form @submit.prevent="submit">
                <div class="columns">
                    <div class="column is-5">
                        <nz-taxon-autocomplete label="Parent"
                            v-model="parentName"
                            @select="onTaxonSelect"
                            :error="form.errors.has('parent_id')"
                            :message="form.errors.first('parent_id')"
                            :taxon="{{ $taxon->parent or 'null' }}"
                            except="{{ $taxon->id }}"
                            autofocus>
                        </nz-taxon-autocomplete>
                    </div>

                    <div class="column is-5">
                        <b-field label="Name"
                            :type="form.errors.has('name') ? 'is-danger' : ''"
                            :message="form.errors.has('name') ? form.errors.first('name') : ''">
                            <b-input v-model="form.name"></b-input>
                        </b-field>
                    </div>

                    <div class="column is-2">
                        <b-field label="Rank"
                            :type="form.errors.has('rank_level') ? 'is-danger' : ''"
                            :message="form.errors.has('rank_level') ? form.errors.first('rank_level') : ''">
                            <b-select placeholder="Select rank" v-model="form.rank_level">
                                <option
                                    v-for="(rank, index) in rankOptions"
                                    :value="rank.value"
                                    :key="index"
                                    v-text="rank.name">
                                </option>
                            </b-select>
                        </b-field>
                    </div>
                </div>

                <div class="columns">
                    <div class="column is-half">
                        <b-field label="(old) FaunaEuropea ID"
                            :type="form.errors.has('fe_old_id') ? 'is-danger' : ''"
                            :message="form.errors.has('fe_old_id') ? form.errors.first('fe_old_id') : ''">
                            <b-input v-model="form.fe_old_id"></b-input>
                        </b-field>
                    </div>

                    <div class="column is-half">
                        <b-field label="FaunaEuropea ID"
                            :type="form.errors.has('fe_id') ? 'is-danger' : ''"
                            :message="form.errors.has('fe_id') ? form.errors.first('fe_id') : ''">
                            <b-input v-model="form.fe_id"></b-input>
                        </b-field>
                    </div>
                </div>

                <hr>

                <button type="submit"
                    class="button is-primary"
                    :class="{
                        'is-loading': form.processing
                    }"
                    @click="submit">
                    Save
                </button>
                <a :href="redirect" class="button is-text">Cancel</a>
            </form>
        </nz-taxon-form>
    </div>
@endsection

@section('breadcrumbs')
    <div class="breadcrumb" aria-label="breadcrumbs">
        <ul>
            <li><a href="{{ route('contributor.index') }}">Dashboard</a></li>
            <li><a href="{{ route('admin.taxa.index') }}">Taxa</a></li>
            <li class="is-active"><a>Edit</a></li>
        </ul>
    </div>
@endsection
