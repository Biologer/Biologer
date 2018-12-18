@extends('layouts.main', ['title' => 'Biodiversity data'])

@section('content')
    <section class="section content">
        <div class="container">
            <h1>Biodiversity data</h1>

            <p class="has-text-justified">
                The term biodiversity has bean mentioned for the first time in eighties,
                denoting the overall variability in plants, animals, fungus and micro-organisms
                on the planet Earth. According to the Convention on biological diversity,
                held in Rio de Janeiro in 1992, the biodiversity has bean defined as
                “variability among living organisms from all sources including, inter alia,
                terrestrial, marine and other aquatic ecosystems and the ecological
                complexes of which they are part; this includes diversity within species,
                between species and of ecosystems“. Since no definition on biodiversity
                is widely accepted, we can say that the biological diversity includes
                all the genes (genetic diversity), species (species diversity) and ecosystems
                (ecosystem diversity) on Earth. The main factors to threat biodiversity
                in our time are habitat changes and fragmentation, overexploitation of resources,
                different kinds of pollutions and introduction of invasive and allochthonous species.
            </p>

            <p class="has-text-justified">
                Collecting data on biological diversity in one country is a basic step
                for evaluation of natural resources and planning the measures for
                biodiversity protection. The project Biologer is intended to do exactly this.
                Biologer community works on gathering data on biodiversity, which is
                significant both for getting better knowledge on taxa distribution,
                but also for evaluating natural values of certain area. In order to
                make gathered data credible and usable, there are three basic steps:
                entry, verification and display of data.
            </p>

            <p class="has-text-justified">
                Each entry of a taxon is consisted from the series of data gathered
                in the field, from the scientific collection or literature. One entry
                can hold data such as: taxa name, longitude, latitude, photo of the record,
                altitude where the taxa is recorded and many other informations. The data
                is entered by User, directly into the database or using Android application,
                using one of available licences for the data. The User can choose the license
                during registration on the platform, in the preferences and/or during the
                individual data entry. We advise Users to stay away from closed data licenses,
                since this diminishes the usability of the records.
            </p>

            <p class="has-text-justified">
                All this data entered in a database are reaching the second step,
                the verification by the Editors. To make identification of the records
                more reliable, the Users are advised to send as much data as possible
                about their field records. Editors are approving the records or mark
                them as nonidentifiable. In each step, the User can supplement his
                records and this can sometimes help identification of the taxa.
            </p>

            <p class="has-text-justified">
                The third step is display of geographic data to other Users and third parties.
                The Display of data is regulated with previously mentioned licences.
                More precise informations about each individual record are available to the
                User that entered the data, Editorial team for the taxonomic group the data
                belongs to and the Administrators. If the data is to be used for some other
                purposes, and the data view is limited (Partially open and Closed licenses)
                Editors and Administrators can decide about the ways to use this data in
                agreement with the User.
            </p>
        </div>
    </section>
@endsection
