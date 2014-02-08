{ezscript_require( array( 'ezjsc::jquery', 
                          'ezjsc::jqueryUI', 
                          'fullcalendar/fullcalendar.min.js'
                        )
                )}
{ezcss_require( array( 'fullcalendar/fullcalendar.css', 'http://jquery-ui.googlecode.com/svn/tags/1.8.18/themes/base/jquery.ui.all.css' ) )}
{ezpagedata_set( 'rightmenu', false())}
<div class="content-view-full">
    <div class="class-agenda">
        <div class="top">
            <div class="bottom">
                <div class="left">
                	<div class="right">
                    	<div class="bottom-left">
                            <div class="bottom-right">
                                <div class="top-right">
                                    <div class="top-left"> 
                                       <div class="container_24">
                                        <div class="padding">

<article class="grid_240">
<div class="box">
    <div class="box-padding">
        <h1 class="f-s color-3 text-shadow">La agenda de SillonBol.com</h1>
    </div>
</div>
<div id="calendar"></div>
</article>

                                        </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{literal}
<script type="text/javascript">
$(document).ready(function() {
    var date = new Date();
		var d = date.getDate();
		var m = date.getMonth();
		var y = date.getFullYear();
   $('#calendar').fullCalendar({
    header: {
        left:   'prev,next today',
        center: 'title',
        right:  'month,agendaWeek,agendaDay'
    },
    theme: true,
    buttonIcons: {
        prev: 'circle-triangle-w',
        next: 'circle-triangle-e'
    },
    buttonText: {
        today: 'hoy',
        month: 'mes',
        day: 'día',
        week: 'semana'
    },
    dayNames: ['Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'],
    dayNamesShort: ['Dom', 'Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab'],
    monthNamesShort: ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'],
    monthNames: ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'],
    allDayText: 'todo el día',
    columnFormat: {
        month: 'ddd',
        week: 'ddd d/M',
        day: 'dddd d/M'
    },
    allDaySlot: false,
    slotMinutes: 60,
    timeFormat: 'H(:mm)',
    firstDay: 1,
    defaultView: 'agendaWeek',
    axisFormat:'HH:mm',
    contentHeight: 800,
    events: [
				{
					title: 'All Day Event',
					start: new Date(y, m, 1)
				},
				{
					title: 'Long Event',
					start: new Date(y, m, d-5),
					end: new Date(y, m, d-2)
				},
				{
					id: 999,
					title: 'Repeating Event',
					start: new Date(y, m, d-3, 16, 0),
					allDay: false
				},
				{
					id: 999,
					title: 'Repeating Event',
					start: new Date(y, m, d+4, 16, 0),
					allDay: false
				},
				{
					title: 'Meeting',
					start: new Date(y, m, d, 10, 15),
					allDay: false,
                    backgroundColor: '#f00',
                    borderColor: '#f00',
				},
				{
					title: 'Lunch',
					start: new Date(y, m, d, 11, 10),
					end: new Date(y, m, d, 14, 0),
					allDay: false
				},
				{
					title: 'Birthday Party',
					start: new Date(y, m, d+1, 19, 0),
					end: new Date(y, m, d+1, 22, 30),
					allDay: false
				},
				{
					title: 'Click for Google',
					start: new Date(y, m, 28),
					end: new Date(y, m, 29),
					url: 'http://google.com/'
				}
			]
        
   });
});
</script>
{/literal}
