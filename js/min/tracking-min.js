jQuery(document).ready(function($){$("a[id$='-phone']").each(function(){$(this).click(function(){var e=$(this).attr("id");ga("send","event",{eventCategory:"event",eventAction:"link",eventLabel:e});var n=mw_tracking_vars.showTrackingAlert;"show"==n&&alert("Currently Tracking: "+e)})})});