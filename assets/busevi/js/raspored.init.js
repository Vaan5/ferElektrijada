var raspored = raspored || {};

raspored.init = function() {
    $(window).bind('beforeunload', function(){
      return 'Jeste li sigurni da želite napustiti stranicu? Sve što niste spremili morat ćete ponovo raditi.';
    });
    $("#saveSchedule").bind("click", function(event) {
        raspored.save();
        event.stopPropagation();
    });
    $("#removeBus").bind("click", function(event) {
        raspored.removeActiveBus();
        event.stopPropagation();
    });
    $("#addBus").bind("click", function(event) {
        raspored.addBus();
        event.stopPropagation();
    });
    $("#addGroup").bind("click", function(event) {
        raspored.addGroup();
        event.stopPropagation();
    });
    $("#setGroupName").bind("click", function(event) {
        raspored.setGroupName();
        event.stopPropagation();
    });
    $("#addToGroup").bind("click", function(event) {
        raspored.addToGroup();
        event.stopPropagation();
    });
    $("#removeFromGroup").bind("click", function(event) {
        raspored.removeFromGroup();
        event.stopPropagation();
    });
    $("#removeGroup").bind("click", function(event) {
        raspored.removeGroup();
        event.stopPropagation();
    });
    $("#addToBus").bind("click", function(event) {
        raspored.addToBus();
        event.stopPropagation();
    });
    $("#setBusCapacity").bind("click", function(event) {
        raspored.setBusCapacity();
        event.stopPropagation();
    });
    $("#setBusPlates").bind("click", function(event) {
        raspored.setBusPlates();
        event.stopPropagation();
    });
    $("#setBusName").bind("click", function(event) {
        raspored.setBusName();
        event.stopPropagation();
    });
    $("#clearBus").bind("click", function(event) {
        raspored.clearBus();
        event.stopPropagation();
    });
    $("input:checkbox").bind("click", function(event) {
        raspored.calculateGroupSize($(this));
        event.stopPropagation();
    });
    $("#fillBuses").bind("click", function(event) {
        raspored.fillBuses();
        event.stopPropagation();
    });
    $("#clearAllBuses").bind("click", function(event) {
        raspored.clearAllBuses();
        event.stopPropagation();
    });
    $("#generateGroups").bind("click", function(event) {
        raspored.generateGroups();
        event.stopPropagation();
    });
    $("#sortBusGroups").bind("click", function(event) {
        raspored.sortBusGroups();
        event.stopPropagation();
    });
    $(".student input[type='text']").bind("click", function(event) {
        event.stopPropagation();
    });
    raspored.bindItem();
    raspored.showHideButtons();
    $('.button-row button, .button-row a, .group-ignore .group-name button, button#sortBusGroups').tooltip({
        position: 'left'
    });
    raspored.calculateUsedOnAllBuses();
    raspored.showHideUnassigned();
    raspored.hideLoader();
};

jQuery.fn.outerHTML = function(s) {
    return s ? this.before(s).remove() : jQuery("<p>").append(this.eq(0).clone()).html();
};

$(document).ready( function() {
    raspored.init();
});