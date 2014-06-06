var raspored = raspored || {};

raspored.activeGroup = null;
raspored.activeGroupName = null;
raspored.addToGroupName = $("#activeGroupName");
raspored.pl_addToGroup = $("#addToGroup");
raspored.groupUnassigned = $("#unassigned");
raspored.activeGroupSize = 0;
raspored.activeGroupId = "";
raspored.customGroupId = 0;

raspored.activeBus = null;
raspored.activeBusCapacity = 0;
raspored.activeBusUsedCapacity = 0;
raspored.pl_activeBusCapacity = null;
raspored.pl_activeBusUsedCapacity = null;
raspored.pl_activeBusUsedPercentage = null;

raspored.groupDictionary = [];

raspored.button = {};
raspored.button.removeBus = $("#removeBus");
raspored.button.addBus = $("#addBus")
raspored.button.addGroup = $("#addGroup");
raspored.button.setGroupName = $("#setGroupName");
raspored.button.addToGroup = $("#addToGroup");
raspored.button.removeFromGroup = $("#removeFromGroup");
raspored.button.removeGroup = $("#removeGroup");
raspored.button.addToBus = $("#addToBus");
raspored.button.setBusCapacity = $("#setBusCapacity");
raspored.button.setBusPlates = $("#setBusPlates");
raspored.button.setBusName = $("#setBusName");
raspored.button.clearBus = $("#clearBus");

raspored.bindItem = function() {
    $("body").on("click", ".group", function(event) {
        raspored.groupClick($(this));
        event.stopPropagation();
    });
    $("body").on("click", ".group-show-hide", function (event){
        raspored.showHideStudents($(this));
        event.stopPropagation();
    });
    $("body").on("click", ".student", function(event) {
        raspored.studentClick($(this));
        event.stopPropagation();
    });
    $("body").on("click", ".bus", function(event) {
        raspored.busClick($(this));
        event.stopPropagation();
    });
    $("body").on("click", ".removeFromBus", function(event) {
        raspored.removeFromBus($(this));
        event.stopPropagation();
    });
    $("body").on("click", ".lockBusGroup", function(event) {
        raspored.lockBusGroup($(this));
        event.stopPropagation();
    });
};

raspored.unbindItem = function() {
    /*$(".group").unbind("click");
    $(".student").unbind("click");
    $(".removeFromBus").unbind("click");
    $(".bus").unbind("click");*/
};

raspored.showHideStudents = function(source) {
    source.closest("div.group").toggleClass("hide-students");
};

raspored.showHideStudents = function(source) {
    source.closest("div.group").toggleClass("hide-students");
};

raspored.showHideButtons = function() {
    var group = raspored.activeGroup !== null;
    var bus = raspored.activeBus !== null;
    if(group) {
        raspored.button.addToGroup.removeAttr("disabled");
        raspored.button.removeFromGroup.removeAttr("disabled");
        raspored.button.removeGroup.removeAttr("disabled");
        raspored.button.setGroupName.removeAttr("disabled");
    }
    else
    {
        raspored.button.addToGroup.attr("disabled", "disabled");
        raspored.button.removeFromGroup.attr("disabled", "disabled");
        raspored.button.removeGroup.attr("disabled", "disabled");
        raspored.button.setGroupName.attr("disabled", "disabled");
    }
    if(bus) {
        raspored.button.removeBus.removeAttr("disabled");
        raspored.button.setBusCapacity.removeAttr("disabled");
        raspored.button.setBusPlates.removeAttr("disabled");
        raspored.button.setBusName.removeAttr("disabled");
        raspored.button.clearBus.removeAttr("disabled");
    }
    else {
        raspored.button.removeBus.attr("disabled", "disabled");
        raspored.button.setBusCapacity.attr("disabled", "disabled");
        raspored.button.setBusPlates.attr("disabled", "disabled");
        raspored.button.setBusName.attr("disabled", "disabled");
        raspored.button.clearBus.attr("disabled", "disabled");
    }
    if(group && bus) {
        raspored.button.addToBus.removeAttr("disabled");
    }
    else
    {
        raspored.button.addToBus.attr("disabled", "disabled");
    }
};

raspored.setActiveGroup = function(target) {
    if(raspored.activeGroup !== null)
        raspored.activeGroup.removeClass("group-active");
    raspored.activeGroup = target;
    raspored.activeGroupName = target.children("div.group-name").html();
    raspored.calculateActiveGroupSize();
    raspored.activeGroupId = raspored.activeGroup.attr("id");
    raspored.activeGroup.addClass("group-active");
    raspored.showHideButtons();
};

raspored.removeActiveGroup = function() {
    if(raspored.activeGroup !== null)
        raspored.activeGroup.removeClass("group-active");
    raspored.activeGroup = null;
    raspored.activeGroupName = "";
    raspored.showHideButtons();
};

raspored.groupClick = function(source) {
    //raspored.unbindItem();
    if(source.data("status") === "disabled")
    {
        alert("Grupa je dodana u autobus. Da biste je mogli mijenjati, morate je izaciti iz autobusa!");
    }
    else
    {
        if(raspored.activeGroup === null)
        {
            raspored.setActiveGroup(source);
        }
        else
        {
            if(raspored.activeGroup.is(source))
            {
                raspored.removeActiveGroup();
            }
            else
            {
                raspored.setActiveGroup(source);
            }
        }
    }
    raspored.addToGroupName.html(raspored.activeGroupName);
    //raspored.bindItem();
};

raspored.showHideUnassigned = function() {
    if(raspored.groupUnassigned.children("div.student").length == 0)
    {
        raspored.groupUnassigned.hide();
        raspored.pl_addToGroup.hide();
    }
    else
    {
        raspored.groupUnassigned.show();
        raspored.pl_addToGroup.show();
    }
};

raspored.calculateActiveGroupSize = function () {
    raspored.activeGroupSize = Math.max(raspored.activeGroup.find("input:checkbox.polazak:checked").length, raspored.activeGroup.find("input:checkbox.odlazak:checked").length);
    raspored.activeGroup.children(".group-size").html(raspored.activeGroupSize);
    //alert(raspored.activeGroupSize);
};

raspored.calculateGroupSize = function (source) {
    var group = source.closest("div.group");
    if(group.length === 0) {
        //alert("Nema!");
    } else {
        //alert("Ima!");
        var size = Math.max(group.find("input:checkbox.polazak:checked").length, group.find("input:checkbox.odlazak:checked").length);
        if(group.is(raspored.activeGroup))
            raspored.activeGroupSize = size;
        group.children(".group-size").html(size);
    }
};

raspored.addToGroup = function() {
    if(raspored.activeGroup === null) {
        alert("Odaberite podgrupu pa ponovite akciju! Podgrupu odabirete tako što kliknete na nju!");
    }
    else
    {
        students = raspored.groupUnassigned.children("div.student-active");
        for(var i=0; i<students.length; i++)
        {
            var element = students.eq(i).detach();
            element.toggleClass("student-active");
            raspored.activeGroup.append(element);
        }
        raspored.calculateActiveGroupSize();

        raspored.showHideUnassigned();
    }
};

raspored.removeFromGroup = function() {
    if(raspored.activeGroup === null) {
        alert("Podgrupa mora biti aktivna kako biste izvršili ovu akciju!");
    }
    else
    {
        students = raspored.activeGroup.children("div.student-active");
        for(var i=0; i<students.length; i++)
        {
            var element = students.eq(i).detach();
            element.toggleClass("student-active");
            raspored.groupUnassigned.append(element);
        }
        raspored.calculateActiveGroupSize();

        raspored.showHideUnassigned();
    }
};

raspored.removeGroup = function() {
    if(raspored.activeGroup === null) {
        alert("Podgrupa mora biti aktivna kako biste izvršili ovu akciju!");
    }
    else
    {
        if(
            confirm("Jeste li sigurni da želite obrisati grupu? Članovi grupe bit će prebačeni u neraspoređene sudionike.")
                ) {
            var name = raspored.activeGroup.children("div.group-name").html();

            delete raspored.groupDictionary[name];

            students = raspored.activeGroup.children("div.student");
            for(var i=0; i<students.length; i++)
            {
                var element = students.eq(i).detach();
                element.removeClass("student-active");
                raspored.groupUnassigned.append(element);
            }
            raspored.addToGroupName.html("");
            raspored.activeGroup.remove();
            raspored.activeGroup = null;

            raspored.showHideUnassigned();
        }
    }
};

raspored.studentClick = function(source) {
    var group = source.closest(".group");
    if(group.length > 0)
    {
        if(group.data("status") !== "disabled")
            source.toggleClass("student-active");
    }
    else {
        source.toggleClass("student-active");
    }
};

raspored.addGroup = function() {
    //raspored.unbindItem();
    var groupContainer = $("#group-container");
    var groupName = prompt("Unesite naziv podgrupe:", "Naziv grupe");
    if(groupName !== null) {
        if(groupName.length === 0)
        {
            alert("Morate unijeti naziv grupe! Ponovite postupak.");
            return false;
        }
        else
        {
            if(raspored.groupDictionary[groupName] !== undefined)
            {
                alert("Grupa sa ovim nazivom već postoji!");
                return;
            }
            var keyValue = "customGroup" + raspored.customGroupId;
            var content = '<div class="col-lg-4 col-md-6 col-xs-12 group hide-students" data-status="enabled" id="' + keyValue + '">' +
                            '<div class="group-show-hide"><span class="glyphicon glyphicon-chevron-right"></span></div>' +
                            '<div class="group-name">' + groupName + '</div>' +
                            '<div class="group-size">0</div>' +
                            '</div>';
            raspored.groupDictionary[groupName] = keyValue;
            raspored.customGroupId++;
            groupContainer.prepend(content);
        }
    }
    //raspored.bindItem();
};

raspored.setGroupName = function() {
    if(raspored.activeGroup === null) {
        alert("Grupa mora biti aktivna da biste izvršili ovu akciju!");
    }
    else
    {
        var name = raspored.activeGroup.children("div.group-name").html();

        var pl_Naziv = raspored.pl
        var newName = prompt("Unesite naziv grupe", name);
        if(newName !== null && newName.length > 0) {
            if(newName !== name) {
                if(raspored.groupDictionary[newName] === undefined)
                {
                    raspored.groupDictionary[newName] = raspored.groupDictionary[name];
                    delete raspored.groupDictionary[name];
                }
                else
                {
                    alert("Grupa sa ovim nazivom već postoji!");
                    return;
                }
            }
            raspored.activeGroupName = newName;
            raspored.activeGroup.children("div.group-name").html(newName);
        }
    }
};

raspored.setActiveBus = function(source) {
    raspored.activeBus = source;
    raspored.pl_activeBusCapacity = source.children(".bus-capacity");
    raspored.pl_activeBusUsedCapacity = source.children(".bus-used");
    raspored.activeBusCapacity = parseInt(raspored.pl_activeBusCapacity.html(), 10);
    raspored.activeBusUsedCapacity = parseInt(raspored.pl_activeBusUsedCapacity.html(), 10);
    raspored.pl_activeBusUsedPercentage = source.children(".bus-percentage").children(".bus-used-capacity");
    raspored.activeBus.toggleClass("bus-active");
    raspored.showHideButtons();
};

raspored.resetActiveBus = function() {
    if(raspored.activeBus !== null)
    {
        raspored.activeBus.removeClass("bus-active");
        raspored.activeBus = null;
        raspored.showHideButtons();
    }
};

raspored.setUsedPercentage = function() {
    var percentage = Math.floor(raspored.activeBusUsedCapacity * 1.0 / raspored.activeBusCapacity * 100);
    percentage = isNaN(percentage) ? 0 : percentage;
    raspored.pl_activeBusUsedPercentage.animate({width: percentage + "%"}, 1000);
    raspored.pl_activeBusUsedPercentage.html(percentage + "%");
};

raspored.setUsedPercentageOfBus = function(bus, busUsed, busSize) {
    bus.children(".bus-used").html(busUsed);
    var pl_busUsedPercentage = bus.children(".bus-percentage").children(".bus-used-capacity");
    var percentage = Math.floor(busUsed * 1.0 / busSize * 100);
    percentage = isNaN(percentage) ? 0 : percentage;
    pl_busUsedPercentage.animate({width: percentage + "%"}, 1000);
    pl_busUsedPercentage.html(percentage + "%");
};

raspored.busClick = function(source) {
    //raspored.unbindItem();
    if(raspored.activeBus === null)
    {
        raspored.setActiveBus(source);
    }
    else
    {
        raspored.activeBus.toggleClass("bus-active");

        if(raspored.activeBus.is(source))
        {
            raspored.resetActiveBus();
        }
        else
        {
            raspored.setActiveBus(source);
        }
    }
    //raspored.bindItem();
};

raspored.setBusCapacity = function() {
    if(raspored.activeBus === null) {
        alert("Bus mora biti aktivan kako biste izvršili ovu akciju!");
    }
    else
    {
        var capacity = prompt("Unesite kapacitet busa:", "52");
        if(capacity !== null && capacity.length > 0 && validateInt(capacity)) {
            var capInt = parseInt(capacity, 10);
            if(capInt < raspored.activeBusUsedCapacity)
            {
                alert("Veličina busa ne smije biti manja od broja zauzetih mjesta. Izbacite neke grupe iz busa kako biste smanjili kapacitet!");
            }
            else
            {
                raspored.activeBusCapacity = capInt;
                raspored.pl_activeBusCapacity.html(capInt);
                raspored.setUsedPercentage();
            }
        }
    }
};

raspored.setBusPlates = function() {
    if(raspored.activeBus === null) {
        alert("Bus mora biti aktivan kako biste izvršili ovu akciju!");
    }
    else
    {
        var plates = prompt("Unesite registracijsku oznaku busa:", "ZG");
        if(plates !== null && plates.length > 0) {
            raspored.activeBus.children(".bus-plates").eq(0).html(plates);
        }
    }
};

raspored.setBusName = function() {
    if(raspored.activeBus === null) {
        alert("Bus mora biti aktivan kako biste izvršili ovu akciju!");
    }
    else
    {
        var label = prompt("Unesite oznaku busa:", "BUS");
        if(label !== null && label.length > 0) {
            raspored.activeBus.children(".bus-name").eq(0).html(label);
        }
    }
};

raspored.disableGroup = function(target) {
    //raspored.unbindItem();
    target.data("status", "disabled");
    target.addClass("disabled");
    target.find("input:checkbox").attr("disabled", "disabled");
    if(raspored.activeGroup !== null && raspored.activeGroup.is(target))
    {
        raspored.removeActiveGroup();
    }
    //raspored.bindItem();
};

raspored.enableGroup = function(target) {
    //raspored.unbindItem();
    target.find("input:checkbox").removeAttr("disabled");
    target.removeClass("disabled");
    target.data("status", "enabled");
    //raspored.bindItem();
};

raspored.addToBus = function() {
    //raspored.unbindItem();
    if(raspored.activeGroup === null) {
        alert("Podgrupa mora biti aktivna kako biste izvršili ovu akciju! Kliknite na podgrupu kako bi postala aktivna!");
    }
    else if(raspored.activeBus === null) {
        alert("Bus mora biti aktivan kako biste izvršili ovu akciju. Klinite na bus da bi postao aktivan!");
    }
    else
    {
        if(raspored.activeGroupSize + raspored.activeBusUsedCapacity > raspored.activeBusCapacity) {
            alert("Grupa ne može stati u ovaj bus!");
        }
        else
        {
            raspored.activeBusUsedCapacity += raspored.activeGroupSize;
            raspored.pl_activeBusUsedCapacity.html(raspored.activeBusUsedCapacity);
            var content = '<div class="bus-group" data-id="' + raspored.activeGroupId + '">' +
                            '<input type="text" class="busGroupOrder" value="N" size="2" maxlength="2">' +
                            '<button type="button" class="btn btn-default btn-sm removeFromBus">' +
                                    '<span class="glyphicon glyphicon-arrow-left"></span>' +
                            '</button>' +
                            '<button type="button" class="btn btn-default btn-sm lockBusGroup">' +
                                    '<span class="glyphicon glyphicon-lock"></span>' +
                            '</button>' +
                            raspored.activeGroupName + ' - ' + raspored.activeGroupSize +
                        '</div>';

            raspored.disableGroup(raspored.activeGroup);
            raspored.activeBus.append(content);
            raspored.setUsedPercentage();
        }
    }
    //raspored.bindItem();
};

raspored.addGroupToBus = function(groupName, groupSize, groupId, bus) {
    var content = '<div class="bus-group" data-id="' + groupId + '">' +
    '<input type="text" class="busGroupOrder" value="N" size="2" maxlength="2">' +
    '<button type="button" class="btn btn-default btn-sm removeFromBus">' +
    '<span class="glyphicon glyphicon-arrow-left"></span>' +
    '</button>' +
    '<button type="button" class="btn btn-default btn-sm lockBusGroup">' +
                                    '<span class="glyphicon glyphicon-lock"></span>' +
                            '</button>' +
    groupName + ' - ' + groupSize +
    '</div>';

    raspored.disableGroup($("#" + groupId));
    bus.append(content);
};

raspored.removeFromBus = function(source) {
    //raspored.unbindItem();
    if(raspored.activeBus === null || !source.parent().parent().is(raspored.activeBus)) {
        alert("Bus mora biti aktivan kako biste izvršili ovu akciju. Klinite na bus da bi postao aktivan!");
    }
    else
    {
        var groupId = "#" + source.parent().data("id");
        var group = $(groupId);

        raspored.activeBusUsedCapacity -= parseInt(Math.max(group.find("input:checkbox.polazak:checked").length, group.find("input:checkbox.odlazak:checked").length));
        raspored.pl_activeBusUsedCapacity.html(raspored.activeBusUsedCapacity);

        source.parent().remove();
        raspored.enableGroup(group);
        raspored.setUsedPercentage();
    }
    //raspored.bindItem();
};

raspored.lockBusGroup = function(source) {
    source.parent().toggleClass("locked");
};

raspored.clearBus = function() {
    //raspored.unbindItem();
    if(raspored.activeBus === null) {
        alert("Bus mora biti aktivan kako biste izvršili ovu akciju. Klinite na bus da bi postao aktivan!");
    }
    else
    {
        var groups = raspored.activeBus.children("div.bus-group:not(.locked)");
        for(var i=0; i<groups.length; i++)
        {
            var source = groups.eq(i);
            var groupId = "#" + source.data("id");
            var group = $(groupId);

            raspored.activeBusUsedCapacity -= parseInt(Math.max(group.find("input:checkbox.polazak:checked").length, group.find("input:checkbox.odlazak:checked").length), 10);
            raspored.pl_activeBusUsedCapacity.html(raspored.activeBusUsedCapacity);

            source.remove();
            raspored.enableGroup(group);
        }
        raspored.setUsedPercentage();
    }
    //console.trace();
    //raspored.bindItem();
};

raspored.clearAllBuses = function() {
    var buses = $(".bus");
    for(var i = 0; i < buses.length; i++)
    {
        raspored.resetActiveBus();
        raspored.setActiveBus(buses.eq(i));
        raspored.clearBus();
    }
    raspored.resetActiveBus();
};

raspored.calculateUsedOnAllBuses = function() {
    var buses = $(".bus");
    for(var i = 0; i < buses.length; i++)
    {
        raspored.resetActiveBus();
        raspored.setActiveBus(buses.eq(i));
        raspored.setUsedPercentage();
    }
    raspored.resetActiveBus();
};

raspored.removeActiveBus = function() {
    //raspored.unbindItem();
    if(raspored.activeBus === null) {
        alert("Bus mora biti aktivan kako biste izvršili ovu akciju. Klinite na bus da bi postao aktivan!");
    }
    else
    {
        if(
            confirm("Jeste li sigurni da želite obrisati autobus? Grupe neće biti obrisane.")
                                                            ) {
            var groups = raspored.activeBus.children(".bus-group");
            for(var i = 0; i < groups.length; i++)
            {
                var busGroup = groups.eq(i);
                var groupId = "#" + busGroup.data("id");
                //alert(groupId);
                var group = $(groupId);
                busGroup.remove();
                raspored.enableGroup(group);
            }
            raspored.activeBus.remove();
            raspored.resetActiveBus();
        }
    }
    //raspored.bindItem();
};

raspored.busNameDictionary = [];
raspored.busPlatesDictionary = [];
raspored.busCount = 1;
raspored.addBus = function() {
    //raspored.unbindItem();
    var busContainer = $("#bus-container");
    var content = '<div class="col-xs-12 bus">' +
                    '<div class="bus-name">BUS ' + raspored.busCount + '</div>' +
                    'Kapacitet:' +
                    '<span class="bus-used">0</span> / <span class="bus-capacity">0</span>' +
                    '<div class="bus-percentage"><div class="bus-used-capacity"></div></div>' +
                    'Registracija:' +
                    '<span class="bus-plates">ZG-000-00' + raspored.busCount + '</span>' +
                  '</div>';
    raspored.busCount++;
    busContainer.prepend(content);
    //raspored.bindItem();
};

raspored.generateGroups = function() {
    var students = raspored.groupUnassigned.children("div.student");
    console.log(students);
    for(var i = 0; i < students.length; i++) {
        var student = students.eq(i);
        var groupName = student.children("input[type='text']").eq(0).val().trim();

        if(groupName !== null && groupName.length === 0)
            continue;

        if(groupName.indexOf(";") > -1) {
            continue;
        }
        else if(groupName.indexOf("::") > -1)
        {
            var split = groupName.split("::");
            //console.log(split);
            split = raspored.clearArray(split);
            //console.log("Novi: " + split);
            if(split.length > 0 && split[0].length > 0)
            {
                groupName = split[0];
            }
            else
            {
                continue;
            }
        }

        //console.log(raspored.groupDictionary[groupName]);
        if(raspored.groupDictionary[groupName] === undefined)
        {
            raspored.addGroupSilent(groupName);
        }
        //console.log(raspored.groupDictionary[groupName]);
        var group = $("#" + raspored.groupDictionary[groupName]);

        if(group.data("status") === "disabled") {
            continue;
        }

        student.removeClass("student-active");

        var studentHtml = student.outerHTML();
        console.log(studentHtml);
        student.remove();
        group.append(studentHtml);
        raspored.calculateGroupSize(group);
    }
    raspored.showHideUnassigned();
};

raspored.addGroupSilent = function(name) {
    //raspored.unbindItem();
    var groupContainer = $("#group-container");
    var groupName = name;
    if(groupName !== null) {
        if(groupName.length === 0)
        {
            return false;
        }
        else
        {
            var keyValue = "customGroup" + raspored.customGroupId;
            var content = '<div class="col-lg-4 col-md-6 col-xs-12 group hide-students" data-status="enabled" id="' + keyValue + '">' +
                            '<div class="group-show-hide"><span class="glyphicon glyphicon-chevron-right"></span></div>' +
                            '<div class="group-name">' + groupName + '</div>' +
                            '<div class="group-size">0</div>' +
                            '</div>';
            raspored.customGroupId++;
            groupContainer.prepend(content);
            raspored.groupDictionary[name] = keyValue;
        }
    }
    //raspored.bindItem();
};

function validateInt(numTxt) {
num = parseInt(numTxt);
return ! (  isNaN(num) ||
            numTxt.indexOf('.') != -1 ||
            numTxt.indexOf(',') != -1 ||
            numTxt.indexOf(' ') != -1 ||
            numTxt.length === 0
            );
}

raspored.sortBusGroups = function() {
    var buses = $(".bus");
    for(var i = 0; i < buses.length; i++)
    {
        //alert("Bus br. " + i);
        $bus = buses.eq(i);
        $bus.children("div.bus-group").tsort('input.busGroupOrder', {useVal:true});
    }
    raspored.resetActiveBus();
};