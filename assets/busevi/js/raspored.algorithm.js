var raspored = raspored || {};

raspored.createArray = function(width, height) {
    var x = new Array(width);
    for (var i = 0; i < width; i++) {
        x[i] = new Array(height);
    }
    return x;
};

raspored.shuffleArray = function(o){ //v1.0
    for(var j, x, i = o.length; i; j = Math.floor(Math.random() * i), x = o[--i], o[i] = o[j], o[j] = x);
    return o;
};

raspored.clearArray = function(array) {
    return array.filter(function(n) {
        return n;
    });
};


raspored.subsetSum = function(n, T, A) {

    var S = raspored.createArray(n+1, T+1),
        chosen = [],
        i = 0,
        j = 0;

    for (i = 0; i <= n; i++)
        S[i][0] = true;
    for (j = 1; j <= T; j++)
        S[0][j] = false;

    for (i = 1; i <= n; i++)
        for (j = 1; j <= T; j++)
            S [i] [j] = S [i - 1] [j] || (A[i-1] <= j && S [i - 1] [j - A [i-1]]);

    /*for(i=1; i<=n; i++)
    {
        var line = "";
        for(j=1; j<=T; j++)
            line += " " + S[i][j];
        console.log(line + "\n");
    }*/

    j = T;
    while(S[n][j] !== true) j--;

    for (i = n; i >= 1; i--)
        if (S[i-1][j] === false)
        {
            //console.log(S[i-1][j]);
            //console.log("chosen " + (i-1) + " = " + A[i-1] + "\n");
            chosen.push(i-1);
            j -= A[i-1];
        }
    return chosen;
};

raspored.permuteBuses = function (input) {
    var set = [];
    function permute (arr, data) {
        var cur, memo = data || [];

        for (var i = 0; i < arr.length; i++) {
           cur = arr.splice(i, 1)[0];
           if (arr.length === 0) set.push(memo.concat([cur]));
                permute(arr.slice(), memo.concat([cur]));
           arr.splice(i, 0, cur);
        }
        return set;
    }
    return permute(input);
};

raspored.fillBuses = function() {

    raspored.resetActiveBus();
    raspored.removeActiveGroup();

    var sizes = [];
    var names = [];
    var ids = [];
    var groupsStatic = $(".group");
    var buses = $(".bus");
    var capacities = [];
    var orderVariants = [];

    var groups = raspored.shuffleArray(groupsStatic);

    for(var i=0; i<groups.length; i++)
    {
        var group = groups.eq(i);
        if(group.data("status") === "disabled") {
            continue;
        }
        var size = Math.max(group.find("input:checkbox.polazak:checked").length, group.find("input:checkbox.odlazak:checked").length);
        var id = group.attr("id");
        var name = group.children(".group-name").html();

        sizes.push(size);
        names.push(name);
        ids.push(id);
    }

    for(var i=0; i<buses.length; i++)
    {
        orderVariants.push(i);

        var bus = buses.eq(i);
        var maxCapacity = parseInt(bus.children(".bus-capacity").html(), 10);
        var usedCapacity = parseInt(bus.children(".bus-used").html(), 10);
        var capacity = maxCapacity - usedCapacity;
        capacities.push(capacity);
    }

    orderVariants = raspored.permuteBuses(orderVariants);
    console.log(orderVariants);

    var selectedVariant = 0;
    var maxTotal = 0;
    for(var k=0; k<orderVariants.length; k++)
    {
        var sizesTest = sizes.slice(0);
        var namesTest = names.slice(0);
        var idsTest = ids.slice(0);
        var total = 0;
        for(var i=0; i<buses.length && sizesTest.length > 0; i++)
        {
            var capacityTest = capacities[orderVariants[k][i]];
            var chosen = new Array();

            if(capacity <= 0)
                continue;

            console.log(sizesTest + " ::: " + capacityTest);

            chosen = raspored.subsetSum(sizesTest.length, capacityTest, sizesTest);

            var sum = 0;
            for(var j = 0; j < chosen.length; j++)
            {
                if(sizesTest[chosen[j]] == undefined)
                    continue;

                //console.log(names[chosen[j]] + " - " + sizes[chosen[j]] + "  ");
                sum += sizesTest[chosen[j]];

                sizesTest.splice(chosen[j], 1);
                namesTest.splice(chosen[j], 1);
                idsTest.splice(chosen[j], 1);
            }
            total += sum;           
        }
        console.log("Variant: " + k + " - " + orderVariants[k] + " :: seats taken: " + total);
        if(total > maxTotal)
        {
            maxTotal = total;
            selectedVariant = k;
        }
    }
    console.log(maxTotal + " <- total, selected variant -> " + selectedVariant);

    for(var i=0; i<buses.length; i++)
    {
        var bus = buses.eq(orderVariants[selectedVariant][i]);
        var maxCapacity = parseInt(bus.children(".bus-capacity").html(), 10);
        var usedCapacity = parseInt(bus.children(".bus-used").html(), 10);
        var capacity = maxCapacity - usedCapacity;
        var chosen = new Array();

        if(capacity <= 0)
            continue;

        console.log(sizes.length + "-" + capacity);
        console.log(sizes);
        chosen = raspored.subsetSum(sizes.length, capacity, sizes);

        var sum = 0;
        for(var j = 0; j < chosen.length; j++)
        {
            if(sizes[chosen[j]] == undefined)
                continue;

            console.log(names[chosen[j]] + " - " + sizes[chosen[j]] + "  ");
            sum += sizes[chosen[j]];
            raspored.addGroupToBus(names[chosen[j]], sizes[chosen[j]], ids[chosen[j]], bus);

            sizes.splice(chosen[j], 1);
            names.splice(chosen[j], 1);
            ids.splice(chosen[j], 1);
        }

        raspored.setUsedPercentageOfBus(bus, usedCapacity+sum, maxCapacity);
        //console.log("Seats taken: " + sum);
    }
};