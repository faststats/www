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
var opp_direction = "right";
history_string = 'History<br>';

function init() {
    'use strict';
    var ft_ele = document.getElementById('ft_spot_left'); 
    var bskt_ele = document.getElementById('basket_left'); 
    var ft_style = getComputedStyle(ft_ele);
    var ft_x = parseInt(ft_style.getPropertyValue("left").split("px")[0], 10);
    var bskt_style = getComputedStyle(bskt_ele);
    var bskt_x = parseInt(bskt_style.getPropertyValue("left").split("px")[0], 10);
    swipe.PxPerFt = (ft_x - bskt_x) / 13.5;
    console.log("swipe.PxPerFt:" + swipe.PxPerFt);
    init_scoreboard();
    court.init();
    grid = document.getElementById('grid');
    grid_style = getComputedStyle(grid);
    grid.addEventListener('touchstart', grid_touchstart, false);
    grid.addEventListener('touchend', grid_touchend, false);
    writePlayerNames();
    //figure out the pixels per foot on the court and store it in the swipe object

}
function swap_opp_direction(e) {
    console.log(e.innerHTML);
    if(e.innerHTML.split(" ")[0] == 'Right') {
        dir_btn = document.getElementById('which_court').innerHTML = "<< Left";
        opp_direction = 'left';
    } else {
        dir_btn = document.getElementById('which_court').innerHTML = "Right >>";
        opp_direction = 'right';
    }
    console.log(opp_direction);
}
// Function to write the player names on the grid
function writePlayerNames() {
    'use strict';
    var i, block_to_insert, container_block, myTop;
    var block_to_insert2, block_to_insert3, name_string;
    //iterate over the array using array data to create player name divs
    for (i = 0; i < players.length; i = i + 1) {
        myTop = swipe.gridTop + swipe.statRow1Ht + (i * swipe.playerRowHt);
        if (players[i].uni > 10000) {
            name_string = players[i].name;
            myTop = myTop + 20;
        } else {
            name_string = players[i].name + " (" + players[i].uni + ")";
        }
        block_to_insert = document.createElement('div');
        block_to_insert.innerHTML = name_string;
        container_block = document.getElementById('grid');
        block_to_insert.setAttribute("style", "color: black;font-weight:bold;font-family:arial; position: fixed; top: " + myTop + "px; left: 2px;");
        container_block.appendChild(block_to_insert);
        if (players[i].uni > 10000) {
            name_string = players[i].name;
            myTop = myTop - 20;
        }
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
//
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
    function init_scoreboard () {
        params = '?event_id=' + event_id;
        params += '&debug=0';
        myURL = 'scoreboard.php' + params;
        console.log(myURL);
        document.getElementById('scoreboard').src = myURL;
        q_click(qtr);
    }

    function q_click(q) {
        quarter = q;
        document.getElementById("qtrbtn_1").className = "qtrbtn";
        document.getElementById("qtrbtn_2").className = "qtrbtn";
        document.getElementById("qtrbtn_3").className = "qtrbtn";
        document.getElementById("qtrbtn_4").className = "qtrbtn";
        document.getElementById("qtrbtn_5").className = "qtrbtn";
        document.getElementById("qtrbtn_6").className = "qtrbtn";
        document.getElementById("qtrbtn_7").className = "qtrbtn";
        document.getElementById("qtrbtn_" + q).className = "btn_pressed";
        console.log("quarter:" + quarter);
    }

//definition of the court class    
court = {
    //Properties
    left : null,
    top : null,
    right : null,
    bottom : null,
    basket_x : null,
    basket_y : null,
    ft_x : null,
    ft_y : null,
    court_image_left : null,
    court_image_right : null,
    court_to_use : null,
    basket_to_use : null,
    ft_to_use : null,
    court_style : null,
    basket_style : null,
    ft_style : null,
    basket : null,
    ft : null,

    init : function () {
        'use strict';
        this.clear();
        if(swipe.player_element == 0) {  
            //that means it is an opponent shot so choose the right court and set it for the top of the screen
            this.top = 1;
            this.bottom = 261;
            this.court_to_use = (opp_direction == 'left') ? 'court_basket_left' : 'court_basket_right';
            this.basket_to_use = (opp_direction == 'left') ? 'basket_left' : 'basket_right';
            this.ft_to_use = (opp_direction == 'left') ? 'ft_spot_left' : 'ft_spot_right';
        } else {
            // this is a 'team' shot 
            this.top = swipe.target_top - 260;
            this.court_to_use = (opp_direction == 'left') ? 'court_basket_right' : 'court_basket_left';
            this.basket_to_use = (opp_direction == 'left') ? 'basket_right' : 'basket_left';
            this.ft_to_use = (opp_direction == 'left') ? 'ft_spot_right' : 'ft_spot_left';
            this.bottom = swipe.target_top;
        }
        this.left = swipe.target_top_center - 105;
        this.right = swipe.target_top_center + 105;
    },

    clear : function () {
        'use strict';
        this.left = null;
        this.top = null;
        this.right = null;
        this.bottom = null;
        this.basket_x = null;
        this.basket_y = null;
        this.ft_x = null;
        this.ft_y = null;
        this.valid_shot_left = null;
        this.valid_shot_top = null;
        this.valid_shot_right =  null;
        this.valid_shot_bottom = null;
        this.court_to_use = null;
        this.basket_to_use = null;
        this.ft_to_use = null;
        this.court_style = null;
        this.basket_style = null;
        this.ft_style = null;
        this.basket = null;
        this.ft = null;
    },
    is_valid_shot : function () {
        'use strict';
        var my_left, my_top, my_right, my_bottom;
        var left_basket_left_edge = 27;
        var left_basket_top_edge = 12;
        var left_basket_right_edge = 203;
        var left_basket_bottom_edge = 246;
        var right_basket_left_edge = 5;
        var right_basket_top_edge = 14;
        var right_basket_right_edge = 181;
        var right_basket_bottom_edge = 246;
console.log(this.left,this.top,this.right,this.bottom);

        if(opp_direction == 'left' && swipe.player_element == 0) {
console.log("opp_dir = left basket and opp");
            my_left = parseInt(this.left) + parseInt(left_basket_left_edge);
            my_top = parseInt(this.top) + parseInt(left_basket_top_edge);
            my_right = parseInt(this.left) + parseInt(left_basket_right_edge);
            my_bottom = parseInt(this.top) + parseInt(left_basket_bottom_edge);
        } else if(opp_direction == 'left' && swipe.player_element >= 1) {
console.log("opp_dir = left basket and us");
            my_left = parseInt(this.left) + parseInt(right_basket_left_edge);
            my_top = parseInt(this.top) + parseInt(right_basket_top_edge);
            my_right = parseInt(this.left) + parseInt(right_basket_right_edge);
            my_bottom = parseInt(this.top) + parseInt(right_basket_bottom_edge);
        } else if(opp_direction == 'right' && swipe.player_element >=1) {
console.log("opp_dir = right basket and us");
            my_left = parseInt(this.left) + parseInt(left_basket_left_edge);
            my_top = parseInt(this.top) + parseInt(left_basket_top_edge);
            my_right = parseInt(this.left) + parseInt(left_basket_right_edge);
            my_bottom = parseInt(this.top) + parseInt(left_basket_bottom_edge);
        } else if(opp_direction == 'right' && swipe.player_element == 0) {
console.log("opp_dir = right basket and opp");
            my_left = parseInt(this.left) + parseInt(right_basket_left_edge);
            my_top = parseInt(this.top) + parseInt(right_basket_top_edge);
            my_right = parseInt(this.left) + parseInt(right_basket_right_edge);
            my_bottom = parseInt(this.top) + parseInt(right_basket_bottom_edge);
        }
console.log(my_left,my_top,my_right,my_bottom);
        if ((swipe.endX >= my_left && swipe.endX <= my_right && swipe.endY >= my_top && swipe.endY <= my_bottom) || (swipe.direction === 'down' && swipe.targetCol.slice(0, 2) === '2m')) {
console.log("it is valid");
            return true;
        } else {
console.log("it is NOT valid");
            return false;
        }
    },

    place : function () {
        'use strict';
        document.getElementById(this.court_to_use).setAttribute("style", "top: " + this.top + "px; left: " + this.left + "px");
        this.basket = document.getElementById(this.basket_to_use);
        this.basket_style = getComputedStyle(this.basket);
        this.basket_x = parseInt(this.left, 10) + parseInt(this.basket_style.getPropertyValue("left").split("px")[0], 10);
        this.basket_y = parseInt(this.top, 10) + parseInt(this.basket_style.getPropertyValue("top").split("px")[0], 10);
console.log("court.basket_to_use:" + this.basket_to_use + "  court.basket_x:" + this.basket_x + "  court.basket_y:" + this.basket_y );
    },

    park : function () {
        'use strict';
        document.getElementById(this.court_to_use).setAttribute("style", "left: 810px;");
    }

};