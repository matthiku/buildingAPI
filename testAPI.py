import requests, datetime, signal, sys
import webbrowser
from collections import OrderedDict


# url list
lclUrl = "http://buildingapi.app/"                     # test
rmtUrl = "http://c-spot.cu.cc/buildingAPI/public/"     # prod

# output file for extensive (non-JSON) html replies
tmpfile = '/wamp/www/pyout.html'



# FUNCTIONS
                        
def signal_handler(signal, frame):
    ''' handle keyboard interrupts '''
    print('Program gracefully ended.')
    sys.exit(0)
# configure the above function as the signal handler
signal.signal(signal.SIGINT, signal_handler)
signal.signal(signal.SIGTERM, signal_handler)

'''  print JSON formatted data '''
def jsonPrint(item, indent, level):
    
    if type(item) is dict:
        level+=1
        print(' ')
        maxw = 1
        for w in item:
            maxw=max(len(w),maxw)
        for w in item:
            if indent==0: indent = len(w)+2
            print(' '*indent*level+w.ljust(maxw)+': ',end='')
            jsonPrint(item[w], indent, level)
            
    elif type(item) is list:
        level+=1
        print(' ')
        for w in item:
            jsonPrint(w, indent, level)
                
    else:
        print(item)

''' write html result into HTML file '''
def nonJson(text):
    if len(text)>500:
        htmldoc = open(tmpfile, 'w')
        htmldoc.write(r.text)
        htmldoc.close()
        print("Result written into temp file", tmpfile)
        # launch this file in your browser
        url = 'file://'+tmpfile
        webbrowser.open_new_tab( 'http://localhost/pyout.html' )
    else:
        print(text)


''' get user to select environment '''
def getEnviron():
    print('-'*50)
    print("L:",lclUrl)
    print("R:",rmtUrl)
    while True:
        where = input("Local or remote? (L/r) ").upper()
        if where in ("L","R", ""): break
    url = lclUrl
    if where == "R": url = rmtUrl
    print('Using:', url, "\n")
    return url

''' get user to select API command '''
def inputCmd():
    # which API command?
    for i in cmds:
        print(i.rjust(3)+": ", cmds[i][0].ljust(7), cmds[i][1])

    while True:
        cmd = input("Enter selection: ")
        if cmd=='': sys.exit()
        try: x=len(cmds[cmd])
        except: continue
        break

    # request missing parameter if API command contains a '?'
    if (cmds[cmd][1].find('?')>0):
        print("API command:", cmds[cmd][1])
        parm = input("Enter missing parameter for this command: ")
        cmds[cmd][1] = cmds[cmd][1].replace('?',parm)
    print('-'*20)
    return cmd

    
''' request a new access token '''
def getToken():
    r = requests.post(url+'oauth/access_token', data=tokenRequest)
    if r.status_code == 200:
        return r.json()['access_token'], r.json()['expires_in']
    print(r.text)    
    
''' create data payload as dict and including the access token management '''
def getPayload(expire, accToken):    
    now = datetime.datetime.now().timestamp()
    # check if token has expired
    if expire - now < 1:
        # get access token first
        accToken, expires_in = getToken()
        # set new expiration date
        expire = now + expires_in
        print("Token expires at", datetime.datetime.fromtimestamp(expire).isoformat())
    print( "requesting", action+":", url + cmds[cmd][1] )            
    # aquire the payload and append the access_token
    payload = cmds[cmd][2].copy()
    payload['access_token'] = accToken
    print("Payload is:", payload)            
    return payload, expire, accToken


# form data
newEvent = {
    'seed'      : '12345',
    'title'     : 'This is the NEW title',
    'rooms'     : '2',
    'status'    : 'OK',
    'start'     : '12:00',
    'end'       : '12:30',
    'nextdate'  : '2016-11-12',
    'repeats'   : 'weekly',
    'weekday'   : 'Monday',
    'targetTemp': '2',
}
tokenRequest = {
    'grant_type'    : 'client_credentials',
    'client_id'     : 'editor',
    'client_secret' : 'wYVXhnXx',
}
newPowerLog = {
    'power'       : 123,
    'boiler_on'   : 1,
    'heating_on'  : 1,
}
newTempLog = {
    'mainroom'   : 21,
    'auxtemp'    : 22,
    'frontroom'  : 23,
    'heating_on' : 24,
    'power'      : 252,
    'outdoor'    : 26,
    'babyroom'   : 27,
}
newEventLog = {
    'event_id'   : 3,
    'eventStart' : '10:00',
    'estimateOn' : '11:11',
    'actualOn'   : '12:22',
#    'actualOff'  : '13:33',  # optional...
}
    
#-------------------------------------
# create a list of API commands
#-------------------------------------
#
# TODO: replace this with the route.php content....
#
# use ordered dictionary to retain order as given
cmds = OrderedDict()
#
# get all resources or a certain one
cmds['1'] = ['GET', 'events',   '']
cmds['2'] = ['GET', 'events/?', '']
# get all resources by a specific status
cmds['3'] = ['GET', 'events/status/?', '']

# get latest POWERlog result
cmds['4'] = ['GET', 'powerlog/latest', '']
# get latest TEMPlog result
cmds['5'] = ['GET', 'templog/latest', '']
# get latest EVENTlog result
cmds['6'] = ['GET', 'eventlog/latest', '']

# acquire access token
cmds['t'] = ['POST', 'oauth/access_token', tokenRequest]

# create a new EVENT resource
cmds['p1'] = ['POST', 'events',   newEvent   ]

# create a new POWERlog resource
cmds['p4'] = ['POST', 'powerlog', newPowerLog]
# create a new TEMPlog resource
cmds['p5'] = ['POST', 'templog',  newTempLog ]
# create a new TEMPlog resource
cmds['p6'] = ['POST', 'eventlog', newEventLog]

# delete a resource
cmds['d1'] = ['DELETE', 'events/?', {}]
# update a resource
cmds['u1'] = ['PATCH',  'events/?', newEvent]


# set token expiration time to now, so that 
# we have to request a new token immediately
expire = datetime.datetime.now().timestamp()
accToken = ''

print('-'*50, "RESTful API access via Python\n(hit 'Ctrl'+c to stop the program)\n")


# Get user to select REMOTE (production) or LOCAL (development)
url = getEnviron()


#
# main loop
#
while True:
    r = ''    # init the request var

    # Which API command?
    print('-'*50)
    cmd    = inputCmd()
    action = cmds[cmd][0]
    
    try:
        # execute GET command
        if action == "GET":
            print("requesting GET:",url+cmds[cmd][1])
            r = requests.get(url + cmds[cmd][1])

        else:
            payload, expire, accToken = getPayload( expire, accToken )

        # execute POST command with data
        if action == "POST":
            r = requests.post( url + cmds[cmd][1], data=payload )

        # execute DELETE command
        if action == "DELETE":
            r = requests.delete( url + cmds[cmd][1], data=payload )
            
        # execute PATCH command
        if action == "PATCH":
            r = requests.patch( url + cmds[cmd][1], data=payload )

    except ConnectionError as  e:
        print('-'*50+"\nConnection to", url, "failed. Try again later:\n", e)
        continue
    except TypeError as  e:
        print('-'*50+"\nConnection to", url, "failed. Try again later:\n", e)
        continue
    except ConnectionResetError as  e:
        print('-'*50+"\nConnection to", url, "failed. Try again later:\n", e)
        continue
    except requests.exceptions.ConnectionError as  e:
        print('-'*50+"\nConnection to", url, "failed. Try again later:\n", e)
        continue
        

    #-------------------------------------
    # show the results
    #-------------------------------------
    rc = r.status_code
    print('='*50, "RESULT:\nHTML status code:", rc)
    try:
        jsonPrint( r.json(), 0, -1 )
    except:
        nonJson( r.text )
    print('='*50)
        

print("Good bye!")
