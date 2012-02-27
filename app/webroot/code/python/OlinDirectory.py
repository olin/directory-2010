"""
Python Bindings to OlinDirectory

Copyright (c) 2010-2011 Jeffrey Stanton, http://nomagicsmoke.com/

Permission is hereby granted, free of charge, to any person obtaining
a copy of this software and associated documentation files (the
"Software"), to deal in the Software without restriction, including
without limitation the rights to use, copy, modify, merge, publish,
distribute, sublicense, and/or sell copies of the Software, and to
permit persons to whom the Software is furnished to do so, subject to
the following conditions:

The above copyright notice and this permission notice shall be
included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
"""

"""
#Sample usage:

import OlinDirectory
od = OlinDirectory.UserAPI()

users = od.findUsers("jeff EH")
for user in users:
    print user

users = od.findUsers(classOf=2010, dorm=EH)
for user in users:
    print user.mobilePhone, user.dormShortName, user.dormRoom

See the User class, below, for a list of all of the information available    
"""

import urllib2;
import json;

class User(object):
    def __init__(self,str=None):
        self.uid           = None
        self.email         = None
        self.isAway        = None
        self.classOf       = None
        self.firstName     = None
        self.lastName      = None
        self.nickName      = None
        self.mailbox       = None
        self.dormRoom      = None
        self.dormID        = None
        self.dormShortName = None
        self.mobilePhone   = None
        self.IM            = {}
    def __str__(self):
        return "%s %s (%s)"%(self.firstName,self.lastName,self.uid)
    def __repr__(self):
        return self.__str__()

class UserAPI(object):
    #API parameters; you should probably leave these be
    API_BASE = "https://acl.olin.edu/directory/api/"
    JSON_FORMAT = "/json"
    USER_SEARCH = "users/search/"
    USER_PROFILE = "users/"
    
    def __init__(self, apiBase=API_BASE):
        self.apiBase = apiBase
    
    def __fetch_json(self, query, method):
        url = self.apiBase + method + urllib2.quote(str(query)) + self.JSON_FORMAT
        handle = urllib2.urlopen(url)
        contents = handle.read()
        del handle
        success = False
        query = None
        num_matches = None
        users_json = None
        res = json.loads(contents)
        if res!=None:
            query = self.__strGet(res,'query')
            num_matches = self.__intGet(res,'numMatches')
            users_json = self.__arrayGet(res,'data')
            success = True
            if(num_matches==None or not isinstance(users_json,list)):
                success = False
        return (query,num_matches,users_json,success)
    
    def __intGet(self,map,key):
        t = map.get(key,None)
        return int(t) if t!=None else None
    
    def __boolGet(self,map,key):
        n = self.__intGet(map,key)
        return n==1 if n!=None else None
    
    def __strGet(self,map,key):
        t = map.get(key,None)
        return str(t) if t!=None else None
    
    def __mapGet(self,map,key):
        return map.get(key,{})
    
    def __arrayGet(self,map,key):
        return map.get(key,[])
    
    def __parseUser(self,json_repr):
        u = User()
        u.uid   = self.__intGet(json_repr,'uid')
        u.email = self.__strGet(json_repr,'email')
        u.isAway = self.__boolGet(json_repr,'isAway')
        u.classOf = self.__intGet(json_repr,'classOf')
        name = self.__mapGet(json_repr,'name')
        u.firstName = self.__strGet(name,'first')
        u.lastName = self.__strGet(name,'last')
        u.nickName = self.__strGet(name,'nick')
        campus = self.__mapGet(json_repr,'campus')
        u.mailbox = self.__strGet(campus,'mailbox')
        dorm = self.__mapGet(campus,'dorm')
        u.dormRoom = self.__strGet(dorm,'room')
        building = self.__mapGet(dorm,'building')
        u.dormID = self.__intGet(building,'id')
        u.dormShortName = self.__strGet(building,'shortName')
        phone = self.__mapGet(json_repr,'phone')
        u.mobilePhone = self.__strGet(phone,'mobile')
        u.IM = self.__mapGet(json_repr,'im')
        return u
    
    def findUsers(self, query=None, uid=None, name=None, classOf=None, email=None, dorm=None, dormShort=None, room=None, phone=None, im=None):
        if(uid!=None): return self.getUser(uid)
        queryParts = []
        if query != None : queryParts.append(str(query))
        if dormShort != None : queryParts.append("name:"+str(name))
        if classOf != None : queryParts.append("class:"+str(classOf))
        if email != None : queryParts.append("email:"+str(email))
        if dorm  != None : queryParts.append("dorm:"+str(dorm))
        if name  != None : queryParts.append("dormShort:"+str(dormShort))
        if room  != None : queryParts.append("room:"+str(room))
        if phone != None : queryParts.append("phone:"+str(phone))
        if im    != None : queryParts.append("im:"+str(im))
        if len(queryParts)==0: #must specify at least one valid query clause
            raise ValueError("Please specify at least one constraint on which to search users")
        (query,num_matches,users_json,success) = self.__fetch_json(" ".join(queryParts), self.USER_SEARCH)
        if not success:
            return None
        users = []
        for raw_user in users_json:
            users.append(self.__parseUser(raw_user))
        return users
    
    def getUser(self,uid):
        (query,num_matches,users_json,success) = self.__fetch_json(uid, self.USER_PROFILE)
        if not success or num_matches==None or num_matches==0 or len(users_json)==0:
            return None
        return self.__parseUser(users_json[0])
