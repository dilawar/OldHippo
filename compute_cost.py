"""compute_cost.py: 

"""
    
__author__           = "Dilawar Singh"
__copyright__        = "Copyright 2016, Dilawar Singh"
__credits__          = ["NCBS Bangalore"]
__license__          = "GNU GPL"
__version__          = "1.0.0"
__maintainer__       = "Dilawar Singh"
__email__            = "dilawars@ncbs.res.in"
__status__           = "Development"

import sys
import os
import random
import datetime
import math

__fmt__ = '%Y-%m-%d'

def computeCost( slot_date, lastDate, nAWS ):
    ndays = ( slot_date - lastDate ).days 
    nyears = ndays / 365.0
    cost = 0
    if ndays < 365.0:
        cost = 20
    else:
        cost = 3 * nyears 
        if nAWS > 1:
            cost +=  (4 * nAWS) * math.exp( - nyears ) - 1.0
    return int(10 * cost)

def random_date(start, end):
    """
    This function will return a random datetime between two datetime 
    objects.
    """
    delta = end - start
    int_delta = (delta.days * 24.0 * 60 * 60) + delta.seconds
    random_second = randrange(int_delta)
    return start + datetime.timedelta(seconds=random_second)

def test( ):
    import pylab
    # Generate random test data.
    start = datetime.datetime.strptime( '2017-03-18', __fmt__ )
    end = datetime.datetime.strptime( '2021-03-18', __fmt__  )
    for naws in range( 0, 6 ):
        xval, yval = [ ], [ ]
        for i in range( 5 * 54 ):
            date = start + datetime.timedelta( days = i * 7 )
            xval.append( (date - start).days / 365.0 )
            yval.append( computeCost( date, start, naws ) )
        pylab.xlabel( 'Gap in years between slot and last AWS' )
        pylab.ylabel( 'Cost' )
        pylab.plot( xval, yval, alpha = 0.7, label = '#AWS = %d' % naws )
        pylab.legend( framealpha = 0.4, fontsize = 8 )

    pylab.savefig( "%s.png" % sys.argv[0] )


if __name__ == '__main__':
    test()
