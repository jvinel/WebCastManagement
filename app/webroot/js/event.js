/*
    This file is part of WebCast Management.

    WebCast Management is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    WebCast Management is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Foobar.  If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Update label associated with each location according to video bit rate specified
 * @returns {undefined}
 */
function updateBitrate() {
   // Update user limit according to video bitrate specified
   if ($('#EventVideoBitrate').val()>0) {
       var eventloc_max=$('#eventloc_max').val();
       var global_result=0;
       for (var cpt=0;cpt<=eventloc_max;cpt++) {
           var location_id=$('#eventloc_' + cpt + '_locationid').val();
           var current_user=$('#eventloc_' + cpt + '_audience').val();
           var current_user_real=$('#eventloc_' + cpt + '_estimated_audience').html();
           var max_user=getMaxUser($('#EventVideoBitrate').val(), bandwidthArray[location_id]);
           $('#eventloc_'+ cpt + '_label').removeClass();
           $('#eventloc_'+ cpt + '_label').addClass("label");
           if (current_user_real>=max_user && current_user>0) {
               $('#eventloc_'+ cpt + '_label').addClass("label-danger");
               global_result=2;
           } else if (current_user> max_user && current_user_real<max_user && current_user>0) {
               $('#eventloc_'+ cpt + '_label').addClass("label-warning");
               if (global_result===0) {
                   global_result=1;
               }
           } else {
               $('#eventloc_'+ cpt + '_label').addClass("label-success");
           }
                $('#eventloc_'+ cpt + '_label').html("Max: " + max_user);

       }
       // Update global label
       $('#audience_status').removeClass();
       $('#audience_status').addClass("label");
       switch (global_result) {
           case 0:
               $('#audience_status').addClass("label-success");
               $('#audience_status').html("Limit not reached");
               break;
           case 1:
               $('#audience_status').addClass("label-warning");
               $('#audience_status').html("Theorical limit reached or exceeded");
               break;
            case 2:
               $('#audience_status').addClass("label-danger");
               $('#audience_status').html("Limit reached or exceeded");
               break;
       } 
   }
}
   
/**
 * Returns max number of user for specified bandwidth & bitrate
 * @param {type} bitrate
 * @param {type} bandwidth
 * @returns {Number}
 */
function getMaxUser(bitrate, bandwidth) {
    return Math.floor((bandwidth * bandwidth_threshold_ratio)/(bitrate*bandwidth_usage_ratio));
}

/**
 * Update estimated audience 
 * @returns {undefined}
 */
function updateEstimatedAudience() {
    var eventloc_max=$('#eventloc_max').val();
    var selected_importance=$("#EventEventImportanceId").val();
    for (var cpt=0;cpt<=eventloc_max;cpt++) {
        var current_user=$('#eventloc_' + cpt + '_audience').val();
        var estimated=Math.floor(current_user*importanceArray[selected_importance]);
        if (current_user>0 && estimated==0) {
            estimated=1;
        }
        $('#eventloc_'+ cpt + '_estimated_audience').html(estimated);
    } 
    updateBitrate();
    updateRecommendedBitrate();
}

/**
 * Update recommanded bitrate according to audience specified
 * @returns {undefined}
 */
function updateRecommendedBitrate() {
       var eventloc_max=$('#eventloc_max').val();
       var min_bitrate=5000;
       var min_bitrate_real=5000;
       for (var cpt=0;cpt<=eventloc_max;cpt++) {
            var location_id=$('#eventloc_' + cpt + '_locationid').val();
            var current_user=$('#eventloc_' + cpt + '_audience').val();
            var current_user_real=$('#eventloc_' + cpt + '_estimated_audience').html();
            var bandwidth=bandwidthArray[location_id];
            if (current_user>0) {
                var bitrate=Math.floor((bandwidth * bandwidth_threshold_ratio)/(current_user*bandwidth_usage_ratio));
                if (bitrate<min_bitrate) {
                    min_bitrate=bitrate;
                }
            }
            if (current_user_real>0) {
                var bitrate_real=Math.floor((bandwidth * bandwidth_threshold_ratio)/(current_user_real*bandwidth_usage_ratio));

                if (bitrate_real<min_bitrate_real) {
                    min_bitrate_real=bitrate_real;
                }
            }
        } 
        $('#recommended_bitrate').html("Recommended bitrate: " + min_bitrate + " (theorical: " + min_bitrate_real + ")");
   }