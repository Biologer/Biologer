<div class="columns">
    <div class="column">
        <div class="field is-required">
            <label class="label">Korištenje podataka</label>
            <p>Izaberite jedan od načina na koji želite da dijelite svoje podatke.</p>
            <div class="control">
                <b-tooltip
                    label="Izborom ove opcije slažete se da dijelite sve podatke o nalazima
                        vrsta (osim u slučaju ugroženih vrsta, za čiji prikaz taksonomski
                        stručnjaci odluče da ograniče prikaz)"
                    multilined>
                    <label class="radio">
                        <input type="radio" name="data_license" value="10"{{ $preferences->data_license == 10 ? ' checked' : '' }}>
                        Javno dostupni podaci (<a href="https://creativecommons.org/licenses/by-sa/4.0/">Creative Commons autorskopravna licenca, autorstvo – dijeliti pod istim uvjetima</a>)
                    </label>
                </b-tooltip>
                <b-tooltip
                    label="Slično gore navedenoj licenci, ali onemogućava komercijalnu
                        upotrebu podataka bez vaše suglasnosti. Vaši će podaci biti
                        dostupni za potrebe zaštite bioraznolikosti i izradu znanstvenih studija."
                    multilined>
                    <label class="radio">
                        <input type="radio" name="data_license" value="20"{{ $preferences->data_license == 20 ? ' checked' : '' }}>
                        Javno dostupni podaci (<a href="https://creativecommons.org/licenses/by-nc-sa/4.0/">Creative Commons autorskopravna licenca, autorstvo-nekomercijalno - dijeliti pod istim uvjetima</a>)
                    </label>
                </b-tooltip>
                <b-tooltip
                    label="Slično kao gore navedene licence, samo što se podaci javno prikazuju
                        u vidu kvadrata veličine 10×10 km. Detaljniji podaci će biti vidljivi
                        Vama, Administratorima baze i taksonomskim stručnjacima."
                    multilined>
                    <label class="radio">
                        <input type="radio" name="data_license" value="30"{{ $preferences->data_license == 30 ? ' checked' : '' }}>
                        Dijelomično dostupni podaci (na nivou kvadrata veličine 10×10 km)
                    </label>
                </b-tooltip>
                <b-tooltip
                    label="Ne preporučujemo izbor ove opcije. Samo Vi, Administratori
                        i taksonomski stručnjaci će moći vidjeti podatke."
                    multilined>
                    <label class="radio">
                        <input type="radio" name="data_license" value="40"{{ $preferences->data_license == 40 ? ' checked' : '' }}>
                        Skriveni podaci (podaci se ne prikazuju na kartama)
                    </label>
                </b-tooltip>
            </div>
        </div>
    </div>

    <div class="column">
        <div class="field is-required">
            <label class="label">Korištenje fotografija</label>
            <p>Izaberite jedan od načina na koji želite da dijelite svoje fotografije.</p>
            <div class="control">
                <b-tooltip
                    label="Izborom ove mogućnosti slažete se da dijelite sve fotografije koje
                        pošaljete u bazu. Tako poslane slike mogu se koristiti i dijeliti
                        uz navođenje imena autora fotografije."
                    multilined>
                    <label class="radio">
                        <input type="radio" name="image_license" value="10"{{ $preferences->image_license == 10 ? ' checked' : '' }}>
                        Javne fotografije (<a href="https://creativecommons.org/licenses/by-sa/4.0/">Creative Commons autorskopravna licenca, autorstvo - dijeliti pod istim uvjetima</a> licence)
                    </label>
                </b-tooltip>
                <b-tooltip
                    label="Slično gore navedenoj licenci, ali onemogućava komercijalnu
                        upotrebu fotografija bez Vaše suglasnosti."
                    multilined>
                    <label class="radio">
                        <input type="radio" name="image_license" value="20"{{ $preferences->image_license == 20 ? ' checked' : '' }}>
                        Javne fotografije (<a href="https://creativecommons.org/licenses/by-nc-sa/4.0/">Creative Commons autorskopravna licenca, autorstvo - nekomercijalno - dijeliti pod istim uvjetima</a> licence)
                    </label>
                </b-tooltip>
                <b-tooltip
                    label="Izborom ove mogućnosti slažete se da Vaše fotografije budu prikazane na web
                        stranici, ali ne dozvoljavate njihovo daljnje korištenje bez Vaše suglasnosti.
                        Slike će dobiti vodeni žig s podacima o licenci i logom web stranice."
                    multilined>
                    <label class="radio">
                        <input type="radio" name="image_license" value="30"{{ $preferences->image_license == 30 ? ' checked' : '' }}>
                        Fotografije su dostupne samo na ovoj stranici (zadržavajući autorstvo)
                    </label>
                </b-tooltip>
                <b-tooltip
                    label="Ne preporučujemo izbor ove opcije. Samo Vi, Administratori i taksonomski
                        stručnjaci će moći vidjeti fotografije, zbog čega ostali korisnici web stranice
                        ne mogu proveriti točnost vaših nalaza."
                    multilined>
                    <label class="radio">
                        <input type="radio" name="image_license" value="40"{{ $preferences->image_license == 40 ? ' checked' : '' }}>
                        ‎Skrivene fotografije (slike se uopće ne prikazuju u javnom dijelu)
                    </label>
                </b-tooltip>
            </div>
        </div>
    </div>
</div>
