<?php 
/** Script includes */
$javascript->link('ui/CodeColorize.js',false);
$javascript->link('ui/APIFormatSwitcher.js',false);
?>

<h1>API: User Functions</h1>

<div class="Right Sidebar">
<h4>Table of Contents</h4>
<?php echo $html->link('API Home [^]', array('action'=>'help','index'));?>
<a href="#fields">Users: Standard Fields</a>
<a href="#search">API Calls:</a>
<a class="SubMenu" href="#search">Search Users</a>
<a class="SubMenu" href="#profile">Get Profile</a>
</div>

<p class="Reminder">(<?php echo $html->link('go back to api home', array('action'=>'help','index'));?>)</p>
<p>The documentation on this page covers the API functions related to retrieving user information from OlinDirectory.  There are two API calls outlined on this page; one to retrieve the information of a specific user, and the other to search for users based on one or more search terms.</p>
<p class='Reminder'>Reminder: all of these API calls are relative to the path <a href="<?php print $absoluteBase;?>/"><?php print $absoluteBase;?>/</a>

<a name="fields"></a><div class="section"><h2>Users: Standard Fields</h2>
<p>All API user functions interact with, and return, a standard set of fields.  If any field is empty or has not been provided by the user,
then that field will be empty (in CSV output) or omitted (in structured output).  In the case of hierarchical structured output
(for example, JSON), the name listed below is the hierarchy path joined by periods.  For sample data in the different structured
and unstructured formats, see the format-specific sections after this one.</p>
<table class="API Fields" cellspacing="0" cellpadding="0">
<tr>
	<td><tt>integer</tt></td>
	<td><strong>uid</strong></td>
	<td>The unique ID of the user</td>
</tr><tr>
	<td><tt>string</tt></td>
	<td><strong>name.first</strong></td>
	<td>The user's first name</td>
</tr><tr>
	<td><tt>string</tt></td>
	<td><strong>name.last</strong></td>
	<td>The user's last name</td>
</tr><tr>
	<td><tt>string</tt></td>
	<td><strong>email</strong></td>
	<td>The user's email address</td>
</tr><tr>
	<td><tt>string</tt></td>
	<td><strong>name.nick</strong></td>
	<td>If provided, the user's stated nickname</td>
</tr><tr>
	<td><tt>bool</tt></td>
	<td><strong>isAway</strong></td>
	<td>Is the user currently away from Olin (study away, LOA, etc...)<br />
		(<em>In fact, this is an integer that is either <tt class="S">0</tt> or <tt class="S">1</tt></em>)
	</td>
</tr><tr>
	<td><tt>integer</tt></td>
	<td><strong>classOf</strong></td>
	<td>The user's expected graduating class</td>
</tr><tr>
	<td><tt>string</tt></td>
	<td><strong>campus.mailbox</strong></td>
	<td>The user's mailbox number on-campus</td>
</tr><tr>
	<td><tt>integer</tt></td>
	<td><strong>campus.dorm.building.id</strong></td>
	<td>The unique ID of the dorm the user lives in</td>
</tr><tr>
	<td><tt>string</tt></td>
	<td><strong>campus.dorm.building.shortName</strong></td>
	<td>The abbreviated name of the dorm the user lives in, usually two letters, e.g. <tt class="S">EH</tt></td>
</tr><tr>
	<td><tt>string</tt></td>
	<td><strong>campus.dorm.building.longName</strong></td>
	<td>The long name of the dorm the user lives in, e.g. <tt class="S">East Hall</tt></td>
</tr><tr>
	<td><tt>string</tt></td>
	<td><strong>campus.dorm.room</strong></td>
	<td>The user's dorm room number on-campus.  This is a string to allow for suites, e.g. <tt class="S">126A</tt></td>
</tr><tr>
	<td><tt>string</tt></td>
	<td><strong>phone.mobile</strong></td>
	<td>The user's mobile phone number, formatted however the user provided it</td>
</tr><tr>
	<td><tt>string</tt></td>
	<td><strong>im.AOL</strong></td>
	<td>The user's AOL screen name</td>
</tr><tr>
	<td><tt>string</tt></td>
	<td><strong>im.GTalk</strong></td>
	<td>The user's Google Talk screen name</td>
</tr><tr>
	<td><tt>string</tt></td>
	<td><strong>im.ICQ</strong></td>
	<td>The user's ICQ screen name</td>
</tr><tr>
	<td><tt>string</tt></td>
	<td><strong>im.MSN</strong></td>
	<td>The user's MSN screen name</td>
</tr><tr>
	<td><tt>string</tt></td>
	<td><strong>im.Skype</strong></td>
	<td>The user's Skype screen name</td>
</tr>
</table>
</div>


<a name="search"></a><div class="section"><h2>"Search Users" API Call</h2>
<p>This API call will return a list of all users found by searching for the query you provide.  At its core, your query is composd of one or more words, separated by spaces.  <em>All user searches</em> are case-insensitive.  A user matches your query if every word from your query appears at least once as a substring or a full match of any of the user's publicly-visible profile fields.</p>
<p><strong>Outputs</strong>: &nbsp;A list of user profile data, following the <a href="#fields">standard fields</a> outlined above.</p>
<p><strong>Syntax</strong>: &nbsp;<tt class="Query">/api/users/search/<span class="P">query</span>/<span class="P">format</span></tt><ul>
<li><tt class="P">query</tt>: Required. One or more strings to search on, separated by spaces, URL-encoded, e.g. <tt class="S">jeff+EH</tt></li>
<li><tt class="P">format</tt>: Optional. One of <tt class="P">json</tt>, or <tt class="P">xml</tt>, or <tt class="P">csv</tt>.
	It defaults to <tt class="P">json</tt> if you don't specify.  The <tt class="P">csv</tt> format will return a flat array of users, one per row; the other formats will all return structured data.</li>
</ul></p>

<h3>Query Syntax</h3>
<p>At its core, a query is composed of one or more words, separated by spaces, such as <tt class="S Query">word1 word2 word3</tt>.  When you search for users with a query, you'll get get back only those users that match <em>every</em> part of your query.</p>
<p>By default, each part of the query is searched for within all of the <a href="#fields">standard fields</a>, described above.  However, you might want to be more specific than that.  Maybe you want to restrict part of your query to only searching a few fields.  Consider the query <tt class="S Query">Jeff 2010</tt>.  This query would match anybody named Jeff in the class of 2010, but it would also match somebody named Jeff with a phone number 781-555-2010.</p>
<p>Instead, you can get more specific.  To get only Jeffs in the class of 2010, you could specify a filter in your query: <tt class="S Query">jeff class:2010</tt>.  That query will match only members of the class of 2010, but will include everybody with "jeff" in their name, email address, or anything else.  Here's a list of all of the filters and the fields that they match:</p>
<table class="API Fields" cellspacing="0" cellpadding="0">
<tr>
	<td><b>filter</b></td>
	<td><b>fields to search</b></td>
	<td><b>example</b></td>
</tr><tr>
	<td><tt>email</tt></td>
	<td><tt>email</tt></td>
	<td><tt>email:@alumni.olin.edu</tt></td>
</tr><tr>
	<td><tt>firstName</tt></td>
	<td><tt>name.first</tt></td>
	<td><tt>firstName:Jeffrey</tt></td>
</tr><tr>
	<td><tt>lastName</tt></td>
	<td><tt>name.last</tt></td>
	<td><tt>lastName:Stanton</tt></td>
</tr><tr>
	<td><tt>nickName</tt></td>
	<td><tt>name.nick</tt></td>
	<td><tt>nickName:Jeffster</tt></td>
</tr><tr>
	<td><tt>name</tt></td>
	<td><tt>name.first</tt>, <tt>name.last</tt>, <tt>name.nick</tt></td>
	<td><tt>name:Jeff</tt></td>
</tr><tr>
	<td><tt>year</tt></td>
	<td><tt>classOf</tt></td>
	<td><tt>year:2010</tt></td>
</tr><tr>
	<td><tt>class</tt></td>
	<td><tt>classOf</tt></td>
	<td><tt>class:2010</tt></td>
</tr><tr>
	<td><tt>mb</tt></td>
	<td><tt>campus.mailbox</tt></td>
	<td><tt>mb:999</tt></td>
</tr><tr>
	<td><tt>dormShort</tt></td>
	<td><tt>campus.dorm.building.shortName</tt></td>
	<td><tt>dormShort:EH</tt></td>
</tr><tr>
	<td><tt>dormLong</tt></td>
	<td><tt>campus.dorm.building.longName</tt></td>
	<td><tt>dormLong:East</tt></td>
</tr><tr>
	<td><tt>dorm</tt></td>
	<td><tt>campus.dorm.building.shortName</tt>, <tt>campus.dorm.building.longName</tt></td>
	<td><tt>dorm:EH</tt></td>
</tr><tr>
	<td><tt>room</tt></td>
	<td><tt>campus.dorm.room</tt></td>
	<td><tt>room:000</tt></td>
</tr><tr>
	<td><tt>phone</tt></td>
	<td><tt>phone.mobile</tt></td>
	<td><tt>phone:781</tt></td>
</tr><tr>
	<td><tt>im</tt></td>
	<td><tt>im.AOL</tt>, <tt>im.GTalk</tt>, <tt>im.ICQ</tt>, <tt>im.MSN</tt>, <tt>im.Skype</tt></td>
	<td><tt>im:imon37</tt></td>
</tr>
</table>
<p>You may freely intermingle as many filters as you want in your query.  For example, the query <a target="_blank" href="<?php print $this->base;?>/api/users/search/class:2010+dorm:EH+room:126/json" class="S Query">class:2010 dorm:EH room:126</a> would match all members of the class of 2010 living in East Hall suite 126.</p>

<h3>Sample Responses</h3>
<p>Here are some samples of the API response in the different supported formats:</p>
<div class="SampleTabGroup" id="sSearch">
<div class="section" id="sSearchJSON">
<h4>JSON Format</h4>
<p>The JSON-formatted data will be output in the form of an array of zero or more structure objects (according to the names of the fields, above).  Leaves will be JSON values; nodes will be JSON objects.</p>
<p>Sample query: <a class="Query" href="<?php print $this->base;?>/api/users/search/jeff+EH/json" target="_blank"><tt class="S">/api/users/search/<span class="P">jeff+EH</span>/<span class="P">json</span></tt></a> <span class="Reminder">(click the sample to open it in a new window)</span></p>
<pre class="Output">
{"query":"jeff EH","numMatches":"2","data":[{"uid":"49","email":"jeff@example.com","name":{"first":"Jeffrey","last":"Stanton"},"isAway":"0","classOf":"2010","campus":{"mailbox":"999","dorm":{"building":{"id":"2","shortName":"EH"},"room":"100A,100D"}},"phone":{"mobile":"781-555-5555"},"im":{"AOL":"myAIMsn","GTalk":"imongtalktoo","Skype":"skypeme"}},{"uid":"50","email":"jeff@ery.com","name":{"first":"Jeffery","last":"McMillan","nick":"Jeffster"},"isAway":"1","classOf":"2011","campus":{"mailbox":"9001","dorm":{"building":{"id":"2","shortName":"EH"},"room":"000"}},"phone":{"mobile":"555-555-5555"}}]}
</pre>
<p>This is the unformatted JSON that the API will output; to make it easier for you to read, let's format it with newlines and indentation:</p>
<pre class="Output Colorized">
{
"query":"jeff EH",
"numMatches":"2",
"data":[
	{
		"uid":"49",
		"email":"jeff@example.com",
		"name":{
			"first":"Jeffrey",
			"last":"Stanton"
		},
		"isAway":"0",
		"classOf":"2010",
		"campus":{
			"mailbox":"999",
			"dorm":{
				"building":{
					"id":"2",
					"shortName":"EH"
				},
				"room":"100A,100D"
			}
		},
		"phone":{
			"mobile":"781-555-5555"
		},
		"im":{
			"AOL":"myAIMsn",
			"GTalk":"imongtalktoo",
			"Skype":"skypeme"
		}
	},
	{
		"uid":"50",
		"email":"jeff@ery.com",
		"name":{
			"first":"Jeffery",
			"last":"McMillan",
			"nick":"Jeffster"
		},
		"isAway":"1",
		"classOf":"2011",
		"campus":{
			"mailbox":"9001",
			"dorm":{
				"building":{
					"id":"2",
					"shortName":"EH"
				},
				"room":"000"
			}
		},
		"phone":{
			"mobile":"555-555-5555"
		}
	}
]
}
</pre>
<p>In the above sample profiles, notice how information omitted by the end-users is also omitted from the data present in the two matched profiles.</p>
<p>Here's a sample of a query that didn't match any users:</p>
<pre class="Output Colorized">
{
	"query":"@olinalumni.com",
	"numMatches":"0",
	"data":[]
}
</pre>
</div>

<div class="section" id="sSearchXML">
<h4>XML Format</h4>
<p>The XML-formatted data will be output in the form of a hierarchy of XML nodes (according to the names of the fields, above).  Leaves will be node attributes; sub-nodes will be sub-tags in XML.</p>
<p>Sample query: <a class="Query" href="<?php print $this->base;?>/api/users/search/jeff+EH/xml" target="_blank"><tt class="S">/api/users/search/<span class="P">jeff+EH</span>/<span class="P">xml</span></tt></a> <span class="Reminder">(click the sample to open it in a new window)</span></p>
<pre class="Output Colorized">
&lt;?xml version=&quot;1.0&quot; encoding=&quot;UTF-8&quot;?&gt; 
&lt;results query=&quot;jeff EH&quot; numMatches=&quot;2&quot;&gt; 
	&lt;user uid=&quot;49&quot; email=&quot;jeff@example.com&quot; isAway=&quot;0&quot; classOf=&quot;2010&quot;&gt; 
		&lt;name first=&quot;Jeffrey&quot; last=&quot;Stanton&quot; /&gt; 
		&lt;campus mailbox=&quot;999&quot;&gt; 
			&lt;dorm room=&quot;100A,100D&quot;&gt; 
				&lt;building id=&quot;2&quot; shortName=&quot;EH&quot; /&gt; 
			&lt;/dorm&gt; 
		&lt;/campus&gt; 
		&lt;phone mobile=&quot;781-555-5555&quot; /&gt; 
		&lt;im AOL=&quot;myAIMsn&quot; GTalk=&quot;imongtalktoo&quot; Skype=&quot;skypeme&quot; /&gt; 
	&lt;/user&gt; 
	&lt;user uid=&quot;50&quot; email=&quot;jeff@ery.com&quot; isAway=&quot;1&quot; classOf=&quot;2011&quot;&gt; 
		&lt;name first=&quot;Jeffery&quot; last=&quot;McMillan&quot; nick=&quot;Jeffster&quot; /&gt; 
		&lt;campus mailbox=&quot;9001&quot;&gt; 
			&lt;dorm room=&quot;000&quot;&gt; 
				&lt;building id=&quot;2&quot; shortName=&quot;EH&quot; /&gt; 
			&lt;/dorm&gt; 
		&lt;/campus&gt; 
		&lt;phone mobile=&quot;555-555-5555&quot; /&gt; 
	&lt;/user&gt; 
&lt;/results&gt;
</pre>
<p>In the above sample profiles, notice how information omitted by the end-users is also omitted from the data present in the two matched profiles.</p>
<p>Here's a sample of a query that didn't match any users:</p>
<pre class="Output">
&lt;?xml version=&quot;1.0&quot; encoding=&quot;UTF-8&quot;?&gt; 
&lt;results query=&quot;@olinalumni.com&quot; numMatches=&quot;0&quot; /&gt;
</pre>
</div>

<div class="section" id="sSearchCSV">
<h4>CSV Format</h4>
<p>The CSV-formatted data will have status information in the first row, column names in the second row, followed by one row for each returned user.</p>
<p>Sample query: <a class="Query" href="<?php print $this->base;?>/api/users/search/jeff+EH/csv" target="_blank"><tt class="S">/api/users/search/<span class="P">jeff+EH</span>/<span class="P">csv</span></tt></a> <span class="Reminder">(click the sample to open it in a new window)</span></p>
<pre class="Output">
2,"jeff EH"
uid,email,name.first,name.last,name.nick,isAway,classOf,campus.mailbox,campus.dorm.building.id,campus.dorm.building.shortName,campus.dorm.room,phone.mobile,im.AOL,im.GTalk,im.ICQ,im.MSN,im.Skype
49,jeff@example.com,Jeffrey,Stanton,,0,2010,999,2,EH,"100A,100D",781-555-5555,myAIMsn,imongtalktoo,,,skypeme
50,jeff@ery.com,Jeffery,McMillan,Jeffster,1,2011,9001,2,EH,000,555-555-5555,,,,,
</pre>
<p>The first row contains two columns - the number of users matched, and a copy of the query.  The second row contains the list of field names.  The third row and onwards is the data for the matched users.  In this example profile, I have not provided a nickname, or an ICQ or MSN screen name.</p>
<p>The CSV formatter will obey strict CSV escaping rules - if a value contains a reserved CSV character, it will be surrounded with double-quotes.</p>
<p>Here's a sample of a query that didn't match any users:</p>
<pre class="Output">
0,@olinalumni.com
uid,email,name.first,name.last,name.nick,isAway,classOf,campus.mailbox,campus.dorm.building.id,campus.dorm.building.shortName,campus.dorm.room,phone.mobile,im.AOL,im.GTalk,im.ICQ,im.MSN,im.Skype
</pre>
</div>

</div><!-- end of SampleTabGroup -->
</div><!-- end of Search Users section -->


<a name="profile"></a><div class="section"><h2>"Get Profile" API Call</h2>
<p>This API call will return the profile information for a single user whose user id (<tt class="P">uid</tt>) you provide.</p>
<p><strong>Outputs</strong>: &nbsp;The profile data of a single user, following the <a href="#fields">standard fields</a> outlined above.</p>
<p><strong>Syntax</strong>: &nbsp;<tt class="Query">/api/users/<span class="P">uid</span>/<span class="P">format</span></tt><ul>
<li><tt class="P">uid</tt>: Required. The integer user ID of the user for whom you want profile information.  e.g. <tt class="S">49</tt></li>
<li><tt class="P">format</tt>: Optional. One of <tt class="P">json</tt>, or <tt class="P">xml</tt>, or <tt class="P">csv</tt>.
	It defaults to <tt class="P">json</tt> if you don't specify.  The <tt class="P">csv</tt> format will return a flat row of user data; the other formats will all return structured data.</li>
</ul></p>
<p>The formatted data this call returns is very similar to the data returned by the <a href="#search">search users</a> API call, outlined above.  There is one key difference between the calls, though.  Although this call returns a list containing users, that list will only ever contain zero or one users, no more.  See the sample API calls and data returned, below, to get a better idea of what this call returns.</p>
<p>Here are some samples of the API response in the different supported formats:</p>

<div class="SampleTabGroup" id="sProfile">
<div class="section" id="sProfileJSON">
<h4>JSON Format</h4>
<p>The JSON-formatted data will be output in the form of an array containing at most one structure object (according to the names of the fields, above).  Leaves will be JSON values; nodes will be JSON objects.</p>
<p>Sample query: <a class="Query" href="<?php print $this->base;?>/api/users/49/json" target="_blank"><tt class="S">/api/users/<span class="P">49</span>/<span class="P">json</span></tt></a> <span class="Reminder">(click the sample to open it in a new window)</span></p>
<pre class="Output">
{"query":"49","numMatches":"1","data":[{"uid":"49","email":"jeff@example.com","name":{"first":"Jeffrey","last":"Stanton"},"isAway":"0","classOf":"2010","campus":{"mailbox":"999","dorm":{"building":{"id":"2","shortName":"EH"},"room":"100A,100D"}},"phone":{"mobile":"781-555-5555"},"im":{"AOL":"myAIMsn","GTalk":"imongtalktoo","Skype":"skypeme"}}]}
</pre>
<p>This is the unformatted JSON that the API will output; to make it easier for you to read, let's format it with newlines and indentation:</p>
<pre class="Output Colorized">
{
"query":"49",
"numMatches":"1",
"data":[
	{
		"uid":"49",
		"email":"jeff@example.com",
		"name":{
			"first":"Jeffrey",
			"last":"Stanton"
		},
		"isAway":"0",
		"classOf":"2010",
		"campus":{
			"mailbox":"999",
			"dorm":{
				"building":{
					"id":"2",
					"shortName":"EH"
				},
				"room":"100A,100D"
			}
		},
		"phone":{
			"mobile":"781-555-5555"
		},
		"im":{
			"AOL":"myAIMsn",
			"GTalk":"imongtalktoo",
			"Skype":"skypeme"
		}
	}
]
}
</pre>
<p>In the above sample profile, notice how information omitted by the end-users is also omitted from the data present in returned profile.</p>
<p>Here's a sample response to a request for a nonexistent user ID:</p>
<pre class="Output Colorized">
{
	"query":"99",
	"numMatches":"0",
	"data":[]
}
</pre>
</div>

<div class="section" id="sProfileXML">
<h4>XML Format</h4>
<p>The XML-formatted data will be output in the form of a hierarchy of XML nodes (according to the names of the fields, above).  Leaves will be node attributes; sub-nodes will be sub-tags in XML.</p>
<p>Sample query: <a class="Query" href="<?php print $this->base;?>/api/users/49/xml" target="_blank"><tt class="S">/api/users/<span class="P">49</span>/<span class="P">xml</span></tt></a> <span class="Reminder">(click the sample to open it in a new window)</span></p>
<pre class="Output Colorized">
&lt;?xml version=&quot;1.0&quot; encoding=&quot;UTF-8&quot;?&gt;
&lt;results query=&quot;49&quot; numMatches=&quot;1&quot;&gt;
	&lt;user uid=&quot;49&quot; email=&quot;jeff@example.com&quot; isAway=&quot;0&quot; classOf=&quot;2010&quot;&gt;
		&lt;name first=&quot;Jeffrey&quot; last=&quot;Stanton&quot; /&gt;
		&lt;campus mailbox=&quot;999&quot;&gt;
			&lt;dorm room=&quot;100A,100D&quot;&gt;
				&lt;building id=&quot;2&quot; shortName=&quot;EH&quot; /&gt;
			&lt;/dorm&gt;
		&lt;/campus&gt;
		&lt;phone mobile=&quot;781-555-5555&quot; /&gt;
		&lt;im AOL=&quot;myAIMsn&quot; GTalk=&quot;imongtalktoo&quot; Skype=&quot;skypeme&quot; /&gt;
	&lt;/user&gt;
&lt;/results&gt;
</pre>
<p>In the above sample profile, notice how information omitted by the end-users is also omitted from the data present in returned profile.</p>
<p>Here's a sample response to a request for a nonexistent user ID:</p>
<pre class="Output">
&lt;?xml version=&quot;1.0&quot; encoding=&quot;UTF-8&quot;?&gt; 
&lt;results query=&quot;99&quot; numMatches=&quot;0&quot; /&gt;
</pre>
</div>

<div class="section" id="sProfileCSV">
<h4>CSV Format</h4>
<p>The CSV-formatted data will have status information in the first row, column names in the second row, followed by up to one row containing the returned user data.  If the ID you provided is not that of a valid user, the third row will not be present.</p>
<p>Sample query: <a class="Query" href="<?php print $this->base;?>/api/users/49/csv" target="_blank"><tt class="S">/api/users/<span class="P">49</span>/<span class="P">csv</span></tt></a> <span class="Reminder">(click the sample to open it in a new window)</span></p>
<pre class="Output">
1,49
uid,email,name.first,name.last,name.nick,isAway,classOf,campus.mailbox,campus.dorm.building.id,campus.dorm.building.shortName,campus.dorm.room,phone.mobile,im.AOL,im.GTalk,im.ICQ,im.MSN,im.Skype
49,jeff@example.com,Jeffrey,Stanton,,0,2010,999,2,EH,"100A,100D",781-555-5555,myAIMsn,imongtalktoo,,,skypeme
</pre>
<p>The first row contains two columns - the number of users matched, and a copy of the query.  The second row contains the list of field names.  The third row is the data for the requested user; the third row will be omitted (and the number of results listed in the first row will be 0) if the UID requested is invalid.  In this example profile, I have not provided a nickname, or an ICQ or MSN screen name.</p>
<p>The CSV formatter will obey strict CSV escaping rules - if a value contains a reserved CSV character, it will be surrounded with double-quotes.</p>
<p>Here's a sample response to a request for a nonexistent user ID:</p>
<pre class="Output">
0,99
uid,email,name.first,name.last,name.nick,isAway,classOf,campus.mailbox,campus.dorm.building.id,campus.dorm.building.shortName,campus.dorm.room,phone.mobile,im.AOL,im.GTalk,im.ICQ,im.MSN,im.Skype
</pre>
</div>

</div><!-- end of SampleTabGroup -->
</div><!-- end of Retrieve Profile section -->
