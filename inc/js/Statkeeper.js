/*jshint esversion: 6 */
var swipe;
var event_id;
var quarter = 0;
var y;
var grid;
var grid_style;
var console;
var document;
var touchstart;
var touchend;
var getComputedStyle;
var grid_touchstart;
var grid_touchend;
var process;
var init;
var players;
var writePlayerNames;
var court;
var team_id;
var team_name;
var history_array = ['--', '--', '--', '--', '--', '--', '--'];
var history_string;
var init_scoreboard;
history_string = 'History<br>';

function init() {
    'use strict';
    init_scoreboard();
    if (document.readyState === "complete") { //codehere }
        court.init();
        grid = document.getElementById('grid');
        grid_style = getComputedStyle(grid);
        grid.addEventListener('touchstart', grid_touchstart, false);
        grid.addEventListener('touchend', grid_touchend, false);
        process();
    }
}
var process = function () {
    'use strict';
    writePlayerNames();
};

// Function to write the player names on the grid
function writePlayerNames() {
    'use strict';
    var i, block_to_insert, container_block, myTop;
    var block_to_insert2, block_to_insert3, name_string;
    //iterate over the array using array data to create player name divs
    for (i = 0; i < players.length; i = i + 1) {
        if (players[i].uni > 10000) {
            name_string = players[i].name;
        } else {
            name_string = players[i].name + " (" + players[i].uni + ")";
        }
        myTop = swipe.gridTop + swipe.statRow1Ht + (i * swipe.playerRowHt);
        block_to_insert = document.createElement('div');
        block_to_insert.innerHTML = name_string;
        container_block = document.getElementById('grid');
        block_to_insert.setAttribute("style", "color: black;font-weight:bold;font-family:arial; position: fixed; top: " + myTop + "px; left: 2px;");
        container_block.appendChild(block_to_insert);

        block_to_insert2 = document.createElement('div');
        block_to_insert2.innerHTML = name_string;
        container_block = document.getElementById('grid');
        block_to_insert2.setAttribute("style", "color: black;font-weight:bold;font-family:arial; position: fixed; top: " + (myTop +20) + "px; left: 440px;");
        container_block.appendChild(block_to_insert2);

        block_to_insert3 = document.createElement('div');
        block_to_insert3.innerHTML = name_string;
        container_block = document.getElementById('grid');
        block_to_insert3.setAttribute("style", "color: black;font-weight:bold;font-family:arial; position: fixed; top: " + (myTop +20) + "px; left: 580px;");
        container_block.appendChild(block_to_insert3);

    }
}

var LibCheck = function () {
    'use strict';
    return "Statkeeper.js is online";
};

//function getCssProperty(elmId, property) {
//    'use strict';
//    var elem = document.getElementById(elmId);
//    return window.getComputedStyle(elem, null).getPropertyValue(property);
//}

var grid_touchstart = function (e) {
    'use strict';
    swipe.startX = e.touches[0].pageX;
    swipe.startY = e.touches[0].pageY;
    swipe.getRow();
    swipe.getCol();
    court.init();
    if (parseInt(swipe.targetCol[0], 10) === 2 || parseInt(swipe.targetCol[0], 10) === 3) {
        if (!swipe.isDelete) {
            swipe.isShot = true;
            court.place();
        }
    }
};

var grid_touchend = function (e) {
    'use strict';
    court.park();
    swipe.endX = e.changedTouches[0].pageX;
    swipe.endY = e.changedTouches[0].pageY;
    console.log(" touchend -- shotX",swipe.endX,"shotY",swipe.endY);
    swipe.process();
};


var display_history = function (history_string) {
    'use strict';
    history_array[6] = history_array[5];
    history_array[5] = history_array[4];
    history_array[4] = history_array[3];
    history_array[3] = history_array[2];
    history_array[2] = history_array[1];
    history_array[1] = history_array[0];
    history_array[0] = history_string;
    document.getElementById("hist_0").innerHTML = history_array[6];
    document.getElementById("hist_1").innerHTML = history_array[5];
    document.getElementById("hist_2").innerHTML = history_array[4];
    document.getElementById("hist_3").innerHTML = history_array[3];
    document.getElementById("hist_4").innerHTML = history_array[2];
    document.getElementById("hist_5").innerHTML = history_array[1];
    document.getElementById("hist_6").innerHTML = history_array[0];
};

var update_scoreboard = function () {
    'use strict';
    var myURL, params;
    params = '?event_id=' + event_id.toString();
    params += '&act=' + swipe.targetCol;
    params += '&opp_id=' + players[0].pid;
    params += '&pid=' + players[swipe.player_element].pid.toString();
    params += '&opponent=' + players[0].name;
    params += '&team=' + team_name;
    params += '&debug=0';
    myURL = 'scoreboard.php' + params;
    document.getElementById('scoreboard').src = myURL;
};

court = {
    //Properties
    left : null,
    top : null,
    right : null,
    bottom : null,
    basket_x : null,
    basket_y : null,
    court_image : null,
    valid_shot_left : null,
    valid_shot_top : null,
    valid_shot_right :  null,
    valid_shot_bottom : null,

    init : function () {
        'use strict';
        this.clear();
        this.top = swipe.target_top - 210;
        this.left = swipe.target_top_center - 130;
        this.right = swipe.target_top_center + 130;
        this.bottom = swipe.target_top;
        this.basket_x = parseInt(this.left + 130, 10);
        this.basket_y = parseInt(this.top + 165, 10);
        this.court_image = window.document.getElementById('court');
        this.valid_shot_left = parseInt(this.left + 10, 10);
        this.valid_shot_top = parseInt(this.top + 4, 10);
        this.valid_shot_right = parseInt(this.right - 15, 10);
        this.valid_shot_bottom = parseInt(this.bottom - 29, 10);
    },

    clear : function () {
        'use strict';
        this.left = null;
        this.top = null;
        this.right = null;
        this.bottom = null;
        this.basket_x = null;
        this.basket_y = null;
        this.valid_shot_left = null;
        this.valid_shot_top = null;
        this.valid_shot_right =  null;
        this.valid_shot_bottom = null;

    },

    is_valid_shot : function () {
        'use strict';
        if ((swipe.endX >= this.valid_shot_left && swipe.endX <= this.valid_shot_right && swipe.endY >= this.valid_shot_top && swipe.endY <= this.valid_shot_bottom) || (swipe.direction === 'down' && swipe.targetCol.slice(0, 2) === '2m')) {
            return true;
        } else {
            return false;
        }
    },

    place : function () {
        'use strict';
        this.court_image.setAttribute("style", "top: " + this.top + "px; left: " + this.left + "px;");
        console.log("Place -- courtX", this.left,"courtY",this.top,"basketX",this.basket_x,"basketY",this.basket_y);
    },

    park : function () {
        'use strict';
        this.court_image.setAttribute("style", "position: fixed; top: 0; left: 810px;");
    }

};