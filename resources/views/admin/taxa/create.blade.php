@extends('layouts.admin')

@section('content')
    <div class="container">
        <section class="section">
            <div class="box">
                <nz-taxon-form inline-template
                    action="{{ route('api.taxa.store') }}"
                    :categories="{{ json_encode(App\Taxon::getCategoryOptions()) }}">
                    <div class="">
                        <div class="columns">
                            <div class="column is-5">
                                <nz-taxon-autocomplete label="Parent"
                                    v-model="parentName"
                                    @select="onTaxonSelect"
                                    :error="form.errors.has('parent_id')"
                                    :message="form.errors.first('parent_id')">
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
                                <b-field label="Category">
                                    <b-select placeholder="Select category" v-model="form.category_level">
                                        <option
                                            v-for="(category, index) in categoryOptions"
                                            :value="category.value"
                                            :key="index"
                                            v-text="category.name">
                                        </option>
                                    </b-select>
                                </b-field>
                            </div>
                        </div>

                        <div class="columns">
                            <div class="column is-half">
                                <b-field label="(old) FaunaEuropea ID"
                                    :type="form.errors.has('old_fauna_europea_id') ? 'is-danger' : ''"
                                    :message="form.errors.has('old_fauna_europea_id') ? form.errors.first('old_fauna_europea_id') : ''">
                                    <b-input v-model="form.old_fauna_europea_id"></b-input>
                                </b-field>
                            </div>

                            <div class="column is-half">
                                <b-field label="FaunaEuropea ID"
                                    :type="form.errors.has('fauna_europea_id') ? 'is-danger' : ''"
                                    :message="form.errors.has('fauna_europea_id') ? form.errors.first('fauna_europea_id') : ''">
                                    <b-input v-model="form.fauna_europea_id"></b-input>
                                </b-field>
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
