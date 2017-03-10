<?php

include_once( "header.php" );
include_once( "methods.php" );
include_once( "database.php" );
include_once( "tohtml.php" );

echo userHTML( );

?>

<?php

$events = getEventsOfUser( $_SESSION['user'] );
if( count( $events ) < 1 )
    echo alertUser( "No upcoming events." );
else 
{
    echo "<h2>You have following upcoming events </h2>";
    foreach( $events as $event )
    {
        $gid = $event['gid'];
        echo '<form method="post" action="user_show_events_edit.php">';
        echo arrayToVerticalTableHTML( $event, "info", ''
            , 'last_modified_on,created_by,external_id,is_public_event,eid'
             );
        echo "<table class=\"show_info\">";
        echo "<tr>";
        echo "<td><button name=\"response\" title=\"Cancel this event\" 
                onclick=\"AreYouSure(this)\" >" . $symbCancel . "</button>
            </td>";
        echo "<td style=\"float:right\">
                <button title=\"Edit this event\" name=\"response\" 
                value=\"edit\">" . $symbEdit . "</button> </td>";
        echo "</tr>";
        echo "</table>";
        echo "<br>";
        echo "<input type=\"hidden\" name=\"gid\" value=\"$gid\">";
        echo '</form>';
    }
}

echo goBackToPageLink( "user.php", "Go back" );

?>
