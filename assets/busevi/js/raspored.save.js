raspored.save = function() {
    raspored.showLoader();

    if(!raspored.validate()) {
        raspored.hideLoader();
        return;
    }

    var busevi = [];
    var buses = $(".bus");
    var sjedalo = 1;
    for(var i = 0; i < buses.length; i++)
    {
        sjedalo = 1;
        var bus = buses.eq(i);
        var naziv = bus.children(".bus-name").html();
        var kapacitet = bus.children(".bus-capacity").html();
        var registracija = bus.children(".bus-plates").html();
        var brojBusa = i+1;

        var grupe = [];
        var groups = bus.children("div.bus-group");
        for(var j=0; j<groups.length; j++)
        {
            var source = groups.eq(j);
            var groupId = "#" + source.data("id");
            var group = $(groupId);

            var nazivGrupe  = group.children("div.group-name").html();
            var osobe = [];
            students = group.children("div.student");
            for(var k=0; k<students.length; k++)
            {
                var student = students.eq(k);
                var idOsobe = student.attr("id");
                var polazak = student.children("input:checkbox.polazak").prop('checked') ? 1 : 0;
                var povratak = student.children("input:checkbox.odlazak").prop('checked') ? 1 : 0;
                var napomena = "nema";
                var brojSjedala = sjedalo++;
                var osoba = {
                    "idSudjelovanja": idOsobe,
                    "polazak": polazak,
                    "povratak": povratak,
                    "napomena": napomena,
                    "brojSjedala": brojSjedala
                };
                osobe.push(osoba);
            }

            var grupa = {
                'nazivGrupe': nazivGrupe,
                'osobe' : osobe
            };
            grupe.push(grupa);
        }
        var autobus = {
            "nazivBusa": naziv,
            "brojBusa": brojBusa,
            "brojMjesta": kapacitet,
            "registracija": registracija,
            "grupe": grupe
        };
        busevi.push(autobus);
    }
    if(busevi.length > 0) {
        $.post( baseUrl + "busevi/spremiRaspored", { 'busevi': busevi }, function(response) {
            raspored.hideLoader();
            alert(response);
            //document.write(response);
        });
    }
    else {
        alert("Ne možete spremati izmjene ukoliko ne dodate barem jedan autobus!");
        raspored.hideLoader();
    }
};

raspored.showLoader = function() {
    $("#loader").show(500);
};

raspored.hideLoader = function() {
    $("#loader").hide(500);
};

function hasDuplicates(array) {
    var valuesSoFar = [];
    for (var i = 0; i < array.length; ++i) {
        var value = array[i];
        if (valuesSoFar[value] === true) {
            return true;
        }
        valuesSoFar[value] = true;
    }
    return false;
}

raspored.validate = function() {
    var grupe = $(".group");
    var naziviGrupa = [];
    for(var i=0; i < grupe.length; i++) {
        naziviGrupa.push(grupe.eq(i).children(".group-name").html());
    }
    if(hasDuplicates(naziviGrupa)) {
        alert("Sve grupe moraju imati jedinstven naziv. Izmjene neće biti spremljene.");
        return false;
    }

    var busevi = $(".bus");
    var naziviBuseva = [];
    var registracijeBuseva = [];
    for(var i=0; i < busevi.length; i++) {
        var bus = busevi.eq(i);
        naziviBuseva.push(bus.children(".bus-name").html());
        registracijeBuseva.push(bus.children(".bus-plates").html());
    }
    if(hasDuplicates(naziviBuseva)) {
        alert("Sve busevi moraju imati jedinstven naziv. Izmjene neće biti spremljene.");
        return false;
    }
    if(hasDuplicates(registracijeBuseva)) {
        alert("Svi busevi moraju imati jedinstvenu registracijsku oznaku. Izmjene neće biti spremljene.");
        return false;
    }

    console.log(naziviGrupa);
    console.log(naziviBuseva);
    console.log(registracijeBuseva);

    return true;
};