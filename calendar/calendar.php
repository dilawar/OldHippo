<?php

include_once( 'calendar/NCBSCalendar.php' );
include_once( 'database.php' );
include_once( 'methods.php' );
include_once( 'tohtml.php' );

function calendarIFrame( )
{
    return '
        <iframe class="google_calendar"
            allowtransparency="true"
            src="' . calendarURL() . '&mode=WEEK"
            style="border: 0" width="800" height="400" frameborder="0"
            scrolling="yes">
        </iframe>
    ';
}


function addEventToGoogleCalendar($calendar_name, $event, $client )
{
}

// This function uses gcalcli command to sync my local caledar with google
// calendar.
function addAllEventsToCalednar( $calendarname, $client )
{
}

function updateEventGroupInCalendar( $gid )
{
    $events = getEventsByGroupId( $gid );
    $calendar = new NCBSCalendar( $_SESSION[ 'oauth_credential' ] );
    foreach( $events as $event )
        $calendar->updateEvent( $event );
}

?>
