/*jshint esversion: 6 */
swipe = {
    //Properties
    startX : null,
    startY : null,
    endX : null,
    endY : null,
    isShot : false,
    isDelete : false,
    targetRow : null,
    targetCol : null,
    hypPx : null,
    hypFt : null,
    shotHypPx : null,
    shotHypFt : null,
    shotSine : null,
//    sine : null,
    layup : 'no',
    direction : null,
    isClick : false,
    isSwipe : false,
    PxPerFt : 5,
    player_element : null,
    act_time : null,
    target_left : null,
    target_right : null,
    target_top : null,
    target_top_center : null,
    court_needed : false,
    click_2_inc_fields : "*TO*FTmade*FTmissed*OReb2*OReb3*ORebFT*DReb2*DReb3*DRebFT*Steal*Assist*Block*Foul*",
    swipe_2_inc_fields : "*2made*2missed*3made*3missed*",
    click_2_dec_fields : "*2delete*3delete*FTdelete*",
    swipe_2_dec_fields : "*TO*OReb2*OReb3*ORebFT*DReb2*DReb3*DRebFT*Steal*Assist*Block*Foul*",

    //Grid Layout Parameters
    gridTop : 212,
    statValidWidth : 40,
    statColWidth : 46,
    playerRowHt : 63,
    statRow1Ht : 30,
    midpointOffset : 23,
    nameLeft : 7,
    nameRight : 88,
    statColsLeft : 93,

    // This object identifies the target stat that was clicked or swiped
    // the T is short for Target, then the first number is the row and the other
    // number(s) are the column so T112 means "top half of the stat row on the DReb2 column"
    targets : {
        T11 : "Name",
        T12 : "TO",
        T13: "2made",
        T14: "2missed",
        T15 : "3made",
        T16 : "3missed",
        T17 : "FTmade",
        T18 : "FTmissed",
        T19 : "OReb2",
        T110 : "OReb3",
        T111 : "ORebFT",
        T112 : "DReb2",
        T113 : "DReb3",
        T114 : "DRebFT",
        T115 : "Block",
        T116 : "Foul",
        T21 : "Name",
        T22 : "TO",
        T23 : "2delete",
        T24 : "2delete",
        T25 : "3delete",
        T26 : "3delete",
        T27 : "FTdelete",
        T28 : "FTdelete",
        T29 : "OReb2",
        T210 : "OReb3",
        T211 : "ORebFT",
        T212 : "DReb2",
        T213 : "DReb3",
        T214 : "DRebFT",
        T215 : "Steal",
        T216 : "Assist"
    },
    //Methods
    getRow : function () { // returns the grid row where the swipe initiated.
        // the statRowHt is used to determine if the top or bottom element is the target
        //first row is Row #1   
        'use strict';
        var myRow, myRowTop;
        this.startX = this.startX.toFixed(0);
        this.startY = this.startY.toFixed(0);
        myRow = 1 + parseInt((this.startY - this.gridTop) / this.playerRowHt, 10);
        this.target_top = this.gridTop + ((myRow - 1) * this.playerRowHt);
        if (this.startY - this.target_top <= this.statRow1Ht) {
            this.targetRow = 1 + '';
        } else {
            this.targetRow = 2 + '';
        }
        this.player_element = parseInt(myRow - 1, 10);
        return true;
    },//end of getRow method

    getCol : function () {
        "use strict";
        var myCol, targetKey;
        if (this.startX <= this.nameRight) {
            myCol = 1 + '';
        } else {
            myCol = parseInt (2 + parseInt((this.startX - this.statColsLeft) / this.statColWidth, 10), 10) + '';
        }
        targetKey = "T" + this.targetRow + myCol;
        this.targetCol = this.targets[targetKey];
        this.target_left = this.statColsLeft + ((myCol - 2) * this.statColWidth);
        this.target_right = this.target_left + this.statValidWidth;
        this.target_top_center = parseInt(this.target_left + 23, 10);
    },

    clear : function () {  // method to clear values from all properties 
        'use strict';
        this.startX = null;
        this.startY = null;
        this.endX = null;
        this.endY = null;
        this.isShot = false;
        this.isDelete = false;
        this.targetRow = null;
        this.targetCol = null;
        this.hypPx = null;
        this.hypFt = null;
        this.direction = null;
        this.isClick = false;
        this.isSwipe = false;
        this.PxPerFt = 5;
        this.player_element = null;
        this.act_time = null;
        this.target_left = null;
        this.target_right = null;
        this.target_top = null;
        this.target_top_center = null;
        this.court_needed = false;
        this.shotHypPx = null;
        this.shotHypFt = null;
        this.shotSine = null;
        court.park();
    },

    process : function () {
        'use strict';
        var d, myURL, params;
        params = '';
        d = new Date();
        this.act_time = d.getTime();
        this.getHyp();
        //if a swipe is on the 2 pointer and down, we get layups and randomness
        if (this.isSwipe && this.isShot && this.direction === 'down' && this.targetCol.slice(0, 2) === '2m') {
            this.layup = 'yes';
            this.shotHypFt = ((Math.random() * 6) + 1).toFixed(1);
            if (this.shotSine >= 0) {
                this.shotSine = Math.random().toFixed(6);
            } else {
                this.shotSine = (-1 * Math.random()).toFixed(6);
            }
        } else if (this.isSwipe && this.isShot && court.is_valid_shot()) {
            //this means the swipe was up and is valid
            if (this.isSwipe && this.isShot && this.targetCol.slice(0, 2) === '2m') {
                if (this.shotHypFt <= 6) {
                    this.layup = 'yes';
                }
                if (this.shotHypFt >= 18) {
                    this.shotHypFt = 18;
                }
            }
            if (this.isSwipe && this.isShot && this.targetCol.slice(0, 2) === '3m') {
               if (this.shotHypFt >= 22) {
                    this.shotHypFt = 21;
                }
                if (this.shotHypFt <= 19.5) {
                    this.shotHypFt = 20;
                }
            }
        }

        // determine the action of the click or swipe
        if (this.isClick) {
            if (this.click_2_inc_fields.indexOf('*' + this.targetCol + '*') >= 0) {
                params = '?act_time=' + this.act_time.toString();
                params += '&quarter=' + quarter.toString();
                params += '&event_id=' + event_id.toString();
                params += '&act=' + this.targetCol;
                params += '&feet=0';
                params += '&sine=0';
                params += '&person_id=' + players[this.player_element].pid.toString();
                params += '&person_name=' + players[this.player_element].name;
                params += '&team_id=' + players[this.player_element].team_id.toString();
                params += '&team_name=' + players[this.player_element].team_name;
                params += '&layup=' + this.layup;
                params += '&opp_id=' + opp_id;
                params += '&opp_name=' + opp_name;
                myURL = 'save_stat.php' + params;
                document.getElementById('helper').src = myURL;
                display_history("Q" + quarter + ":" + players[this.player_element].name + " - " + this.targetCol + ' - increment');
                if (this.targetCol === 'FTmade') {
                    update_scoreboard();
                }
            } else if (this.click_2_dec_fields.indexOf('*' + this.targetCol + '*') >= 0) {
                params = "?event_id=" + event_id.toString();
                params += '&person_id=' + players[this.player_element].pid.toString();
                params += '&act=' + this.targetCol;
                myURL = 'delete_stat2.php' + params;
                document.getElementById('helper').src = myURL;
                display_history("Q" + quarter + ":" + players[this.player_element].name + " - " + this.targetCol + ' - decrement');
                if ('**2delete*3delete*FTdelete*'.indexOf('*' + this.targetCol + '*') >= 1) {
                    update_scoreboard();
                }
            } else {
                console.log('No action to take for this click.');
            }
        } else if (this.isSwipe) {
            if (this.swipe_2_inc_fields.indexOf('*' + this.targetCol + '*') >= 0) {
                params = '?act_time=' + this.act_time.toString();
                params += '&quarter=' + quarter.toString();
                params += '&event_id=' + event_id.toString();
                params += '&act=' + this.targetCol;
                params += '&feet=' + this.shotHypFt;
                params += '&sine=' + this.shotSine;
                params += '&person_id=' + players[this.player_element].pid.toString();
                params += '&person_name=' + players[this.player_element].name;
                params += '&team_id=' + players[this.player_element].team_id.toString();
                params += '&team_name=' + players[this.player_element].team_name;
                params += '&layup=' + this.layup;
                myURL = 'save_stat.php' + params;
                document.getElementById('helper').src = myURL;
                display_history("Q" + quarter + ":" + players[this.player_element].name + " - " + this.targetCol + ' - increment');
                update_scoreboard();
            } else if (this.swipe_2_dec_fields.indexOf('*' + this.targetCol + '*') >= 0) {
                params = "?event_id=" + event_id.toString();
                params += '&person_id=' + players[this.player_element].pid.toString();
                params += '&act=' + this.targetCol;
                myURL = 'delete_stat2.php' + params;
                document.getElementById('helper').src = myURL;
                display_history("Q" + quarter + ":" + players[this.player_element].name + " - " + this.targetCol + ' - decrement');
            } else {
                console.log('No action to take for this swipe.');
            }
        }
        this.clear();
        court.clear();
    },


    getHyp : function () {
        'use strict';
        var chg_x, chg_y, shot_chg_x, shot_chg_y;
        chg_x = parseInt(this.endX, 10) - parseInt(this.startX, 10);
        chg_y = parseInt(this.endY, 10) - parseInt(this.startY, 10);
        this.hypPx = parseInt(Math.sqrt(parseInt(chg_x * chg_x, 10) + parseInt(chg_y * chg_y, 10)).toFixed(0), 10);
        this.hypFt = Math.round((this.hypPx / this.PxPerFt).toFixed(1));
        this.shotSine = (parseInt(chg_x, 10) / parseInt(this.hypPx, 10)).toFixed(6);
        if (isNaN(this.shotSine)) {
            this.shotSine = 0;
        }
        if (this.hypPx <= 5) {
            this.isClick = true;
            this.isSwipe = false;
        } else if (this.hypPx <= 30) {
            this.isClick = false;
            this.isSwipe = false;
        } else {
            this.isClick = false;
            this.isSwipe = true;
        }
        if (parseInt(this.startY, 10) - parseInt(this.endY, 10) < 0) {
            this.direction = 'down';
        } else {
           this.direction = 'up';
        }
        if (this.isShot) {
            shot_chg_x = parseInt(this.endX, 10) - parseInt(court.basket_x, 10);
            shot_chg_y = parseInt(this.endY, 10) - parseInt(court.basket_y, 10);
            this.shotHypPx = Math.sqrt((shot_chg_x * shot_chg_x) + (shot_chg_y * shot_chg_y)).toFixed(0);
            this.shotHypFt = Math.round((this.shotHypPx / this.PxPerFt).toFixed(1));
            this.shotSine = (shot_chg_x / this.shotHypPx).toFixed(6);
        }
    }
};//end of stat_swipe object
