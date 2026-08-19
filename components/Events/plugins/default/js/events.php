//<script>
Ossn.RegisterStartupFunction(function() {
	$(document).ready(function() {
		$('body').on('click', '.ossn-wall-container-menu-events', function() {
			Ossn.redirect('event/add');
		});
		if ($('#event-location-input').length) {
			//Location autocomplete not working over https #1043
			//Change to places js
			var placesAutocomplete = places({
				container: document.querySelector('#event-location-input')
			});
		}
		$('body').on('click', '.event-relation', function() {
			$guid = $(this).attr('data-guid');
			$type = $(this).attr('data-type');
			Ossn.MessageBox('event/relations/' + $guid + '?type=' + $type);
		});
		$('body').on('click', '.ossn-wall-container-menu-eventsgroups', function() {
			$url = window.location.href;
			$url = $url.replace(Ossn.site_url, '');
			$url = $url.replace('group/', '');
			Ossn.redirect('event/add/group/' + $url);
		});
		$('body').on('click', '.ossn-wall-container-menu-eventsbusinesspage', function() {
			$url = window.location.href;
			$url = $url.replace(Ossn.site_url, '');
			$url = $url.replace('page/view/', '');
			$url = $url.replace('company/', '');
			Ossn.redirect('event/add/businesspage/' + $url);
		});		
	});
});

function ossn_event_calendar(isgroup = 0, isbusinesspage=0) {
	$(document).ready(function() {
		Ossn.PostRequest({
			url: Ossn.site_url + "action/event/calander/json",
			params: 'is_group=' + isgroup + "&is_businesspage=" + isbusinesspage,
			callback: function(jsonEvents) {
				$('#eventCal').find('.oloading-c').remove();
				const ec = new EventCalendar(document.getElementById('eventCal'), {
					view: 'timeGridWeek',
					locale: 'en',
					allDaySlot: false,
					buttonText: {
						close: Ossn.Print('event:cal:close'),
						dayGridMonth: Ossn.Print('event:cal:month'),
						next: Ossn.Print('event:cal:next'),
						prev: Ossn.Print('event:cal:previous'),
						listDay: Ossn.Print('event:cal:list'),
						listMonth: Ossn.Print('event:cal:list'),
						listWeek: Ossn.Print('event:cal:list'),
						listYear: Ossn.Print('event:cal:list'),
						resourceTimeGridDay: Ossn.Print('event:cal:resources'),
						resourceTimeGridWeek: Ossn.Print('event:cal:resources'),
						resourceTimelineDay: Ossn.Print('event:cal:timeline'),
						resourceTimelineMonth: Ossn.Print('event:cal:timeline'),
						resourceTimelineWeek: Ossn.Print('event:cal:timeline'),
						timeGridDay: Ossn.Print('event:cal:day'),
						timeGridWeek: Ossn.Print('event:cal:week'),
						today: Ossn.Print('event:cal:today')
					},
					headerToolbar: {
						start: 'prev,next today',
						center: 'title',
						end: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
					},
					events: jsonEvents,
					eventClick: function(info) {
						if(typeof info.event.extendedProps.url != 'undefined'){
								window.location = info.event.extendedProps.url;	
						}
					},
					dayMaxEvents: true,
					nowIndicator: true,
					selectable: true
				});
			},
		});
	});
}