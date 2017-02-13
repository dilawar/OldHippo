<?php

include_once 'check_access_permissions.php';
mustHaveAnyOfTheseRoles( array( 'USER' ) );

include_once 'database.php';
include_once 'tohtml.php';
include_once 'methods.php';

echo userHTML( );

// Logic for POST requests.
$speaker = array( 
    'first_name' => '', 'middle_name' => '', 'last_name' => '', 'email' => ''
    , 'department' => '', 'institute' => '', 'title' => '', 'id' => ''
    , 'homepage' => ''
    );

$whereExpr = "created_by='" . $_SESSION[ 'user' ] . "'";
$whereExpr .= "AND status!='INVALID'";
$talks = getTableEntries( 'talks', '', $whereExpr );
if( count( $talks ) < 1 )
{
    echo printInfo( "You don't have any upcoming or unscheduled talk" );
}

foreach( $talks as $t )
{
    echo '<form method="post" action="user_manage_talks_action.php">';
    echo '<table border="0">';
    echo '<tr>';
    echo arrayToTableHTML( $t, 'info', '', 'created_by,status');
    echo '</tr><tr>';
    echo '
        <input type="hidden" name="id" value="' . $t[ 'id' ] . '" />
        <td><button onclick="AreYouSure(this)" name="response" 
            title="Delete this entry" >' . $symbDelete . '</button></td>
        <td><button style="float:right" title="Schedule this talk" 
            name="response" value="schedule">' . $symbCalendar . '</button></td>
        <td><button style="float:right" title="Edit this entry"
                name="response" value="edit">' . $symbEdit . '</button></td>
        ';
    echo '</tr></table>';
    echo '</form>';

    // Check if this talk has already been approved or in pending approval.
    $event = getTableEntry( 'events', 'external_id'
        , array( 'external_id' => 'talks.' . $t[ 'id' ] )
        );
    $event = getTableEntry( 'events', 'external_id,status'
        , array( 'external_id' => 'talks.' . $t[ 'id' ], 'status' => 'VALID' )
        );
    if( $event )
    {
        echo "<strong>Following talk has been confirmed</strong>";
        $html = arrayToTableHTML( $request, 'event', ''
            , 'external_id,url,modified_by,timestamp' );
        echo $html;
    }

    $request = getTableEntry( 'bookmyvenue_requests', 'external_id,status'
        , array( 'external_id' => 'talks.' . $t[ 'id' ], 'status'  => 'PENDING' )
        );

    if( $request )
    {
        echo "<strong>Booking request for above talk is pending review</strong>";
        $html = arrayToTableHTML( $request, 'request', ''
            , 'external_id,url,modified_by,status,timestamp' );
        echo $html;
    }

}
    
echo goBackToPageLink( "user.php", "Go back" );

?>
