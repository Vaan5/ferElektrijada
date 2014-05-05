raspored.save = function() {
    raspored.showLoader();

    var busevi = [];
    var buses = $(".bus");
    for(var i = 0; i < buses.length; i++)
    {
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
                var brojSjedala = 0;
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
        $.post( "/ferElektrijada/busevi/spremiRaspored", { 'busevi': busevi }, function(response) {
            raspored.hideLoader();
            alert("Izmjene su uspješno spremljene");
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