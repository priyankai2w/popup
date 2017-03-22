jQuery(document).ready(function($) {
     $( "#datepicker1" ).datepicker({    
      showOn: "button",
                buttonImage: "http://jqueryui.com/resources/demos/datepicker/images/calendar.gif",
                buttonImageOnly: true ,
                changeMonth: true,//this option for allowing user to select month
                changeYear: true,
                yearRange: "1800:2150" 
                alert('hello');
     });
 });