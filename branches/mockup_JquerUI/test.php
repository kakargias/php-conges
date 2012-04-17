<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
		<title>jQuery UI Example Page</title>
		<link type="text/css" href="css/trontastic/jquery-ui-1.8.18.custom.css" rel="stylesheet" />	
		<script type="text/javascript" src="js/jquery-1.7.1.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui-1.8.18.custom.min.js"></script>
		<script type="text/javascript" src="js/jquery.ui.datepicker-fr.js"></script>
		<script type="text/javascript">
			$(function(){

				// Accordion
				$("#accordion").accordion({ header: "h3" });
	
				// Tabs
				$('#tabs').tabs();
	

				// Dialog			
				$('#dialog').dialog({
					autoOpen: false,
					width: 600,
					buttons: {
						"Ok": function() { 
							$(this).dialog("close"); 
						}, 
						"Cancel": function() { 
							$(this).dialog("close"); 
						} 
					}
				});
				
				// Dialog Link
				$('#dialog_link').click(function(){
					$('#dialog').dialog('open');
					return false;
				});

				// Datepicker
				$('#datepicker').datepicker({
				
					inline: true,
					onSelect:function(dateText, inst){alert(dateText)}
				});
				
				// Slider
				$('#slider').slider({
					range: true,
					values: [17, 67]
				});
				
				// Progressbar
				$("#progressbar").progressbar({
					value: 20 
				});
				
				//hover states on the static widgets
				$('#dialog_link, ul#icons li').hover(
					function() { $(this).addClass('ui-state-hover'); }, 
					function() { $(this).removeClass('ui-state-hover'); }
				);
				
			});
		</script>
		<style type="text/css">
			/*demo page css*/
			body{ font: 62.5% "Trebuchet MS", sans-serif; margin: 50px;}
			.demoHeaders { margin-top: 2em; }
			#dialog_link {padding: .4em 1em .4em 20px;text-decoration: none;position: relative;}
			#dialog_link span.ui-icon {margin: 0 5px 0 0;position: absolute;left: .2em;top: 50%;margin-top: -8px;}
			ul#icons {margin: 0; padding: 0;}
			ul#icons li {margin: 2px; position: relative; padding: 4px 0; cursor: pointer; float: left;  list-style: none;}
			ul#icons span.ui-icon {float: left; margin: 0 4px;}
		</style>	
	</head>
	<body>
	<div id="wrapper">
	<div id="banner">
	<div id="navigation">
	    
    
    
    
    
    
			<div class="left"></div>
			<ul>
				<li>
					<a href="#">Nouvelle Absence</a>
				</li>
				<li>
					<a href="#">Demandes en cours</a>
				</li>
				<li>
					<a href="#">Historique Conges</a>
				</li>				
				<li>
					<a href="#">Historique absences</a>
				</li>
				<li>
					<a href="#">mot de passe</a>
				</li>
				<li class="last">
					<a href="#">Support</a>
				</li>
			</ul>
			<div class="right"></div>
		</div>
	</div>
	<h1>Nouvelle APPLI CONGES!</h1>
	<p style="font-size: 1.3em; line-height: 1.5; margin: 1em 0; width: 50%;">
	Préparation de la nouvelle app congés.
	</p>	

	<p style="font-weight: bold; margin: 2em 0 1em; font-size: 1.3em;">A Faire:</p>
	
	<!-- 1er test-->

<script type="text/javascript">
// <![CDATA[
$(function(){
  var bookedDays = ["2012-03-28","2010-6-12","2010-6-14"];
  
  function assignCalendar(id){
    $('<div class="calendar" />')
      .insertAfter( $(id) )
      .datepicker({ 
        dateFormat: 'dd-mm-yy', 
        minDate: new Date(), 
        maxDate: '+1y', 
        altField: id, 
        beforeShowDay: isAvailable })
      .prev().hide();
  }
  
  function isAvailable(date){
    var dateAsString = date.getFullYear().toString() + "-" + (date.getMonth()+1).toString() + "-" + date.getDate();
    var result = $.inArray( dateAsString, bookedDays ) ==-1 ? [true] : [false];
    return result
  }

  assignCalendar('#startdate');
  assignCalendar('#enddate');
});
// ]]>
</script>
<!-- test2 -->
<script>
	$(function() {
		$( "#datepicker" ).datepicker( $.datepicker.regional[ "fr" ] );
		var bookedDays = ["2012-03-28","03/28/2012","2010-6-14"];
		//$.datepicker.setDefaults($.datepicker.regional['fr']);
		var dates = $( "#from, #to" ).datepicker({
			// defaultDate: "+1w",
			dateFormat : "dd-mm-yy",
			//beforeShowDay:$.datepicker.noWeekends,
			// beforeShow:  isAvailable,
			beforeShowDay: disabledays,
			changeMonth: true,
			//numberOfMonths: 1,
			onSelect: function( selectedDate ) {
				var option = this.id == "from" ? "minDate" : "maxDate",
					instance = $( this ).data( "datepicker" ),
					date = $.datepicker.parseDate(
						instance.settings.dateFormat ||
						$.datepicker._defaults.dateFormat,
						selectedDate, instance.settings );
				//alert(selectedDate);
				dates.not( this ).datepicker( "option", option, date );
			}
		});
	});
	
	function mondayAndFriday(date) {
    var day = date.getDay();
    return [(day == 0 || day == 2), ''];
}
var unavailableDates = ["28-3-2012"];
function disabledays(date) {
    dmy = date.getDate() + "-" + (date.getMonth() + 1) + "-" + date.getFullYear();
    if ($.inArray(dmy, unavailableDates) == 0) {
        return [false, "", "Unavailable"]
    } else {
        var day = date.getDay();
        return [(day != 0 && day != 6)];
    }

}
  function isAvailable(date){
  var bookedDays = ["2012-3-28","2012-3-29","2012-3-30"];
    var dateAsString = date.getFullYear().toString() + "-" + (date.getMonth()+1).toString() + "-" + date.getDate();
	// alert(dateAsString)
    // var result = $.inArray( dateAsString, bookedDays ) ==-1 ? [true] : [false];
    var result = $.inArray( dateAsString, bookedDays ) ==-1 ? [true] : [false];
    return result
  }
  // function isAvailable(date){
    // var dateAsString = date.getFullYear().toString() + "-" + (date.getMonth()+1).toString() + "-" + date.getDate();
    // var result = $.inArray( dateAsString, bookedDays ) ==-1 ? [true] : [false];
    // return result
  // }
	// function nonWorkingDates(date) {
      // var Sunday = 0, Monday = 1, Tuesday = 2, Wednesday = 3, Thursday = 4, Friday = 5, Saturday = 6;
      // if (date.getDay() == Sunday) {
            // return [false, '']; // Closed day of week
      // }
      // var closedDates = [[29, 3, 2012], [25, 3, 2012]];
      // for (var i = 0; i < closedDays.length; i++) {
            // if (date.getDate() == closedDates[i][0] && date.getMonth() == closedDates[i][1] &&
                        // date.getFullYear() == closedDates[i][2]) {
                  // return [false, '']; // Closed date
            // }
      // }
      // return [true, '']; // Open
// }
	</script>



<div class="demo">

<label for="from">From</label>
<input type="text" id="from" name="from"/>
<label for="to">to</label>
<input type="text" id="to" name="to"/>

</div><!-- End demo -->



<div class="demo-description">
<p>Select the date range to search for.</p>
</div><!-- End demo-description -->
	</div>
	</body>
</html>


