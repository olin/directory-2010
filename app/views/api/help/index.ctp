<?php 
/** Script includes */
$javascript->link('ui/CodeColorize.js',false);
?>

<h1>API: Documentation</h1>

<div class="Right Sidebar">
<h4>Table of Contents</h4>
<a href="#users">Users</a>
<a class="SubMenu" href="#usersearch">Search Users</a>
<a class="SubMenu" href="#userprofile">Get Profile</a>
<a href="#impl">API Clients</a>
<a class="SubMenu" href="#impl.py">Python</a>
</div>

<p>The OlinDirectory API is simple and easy to use, and can return the results in a variety of formats.
Presently, prior authentication is not required - you may use the API without needing any credentials.   However,
OlinDirectory is only accessible from inside of Olin's network, and this goes for the API too.</p>

<p>All API calls are relative to the path <a href="<?php print $absoluteBase;?>/api"><?php print $absoluteBase;?>/api</a>

<a name="users"></a><div class="section"><h2>Users</h2>
<p>There are two API calls related to users in OlinDirectory: one to retrieve the information of a specific user, and another to search for users based on one or more search terms.</p>
<p>The available API calls are outline below, or in much greater detail in the <?php echo $html->link('user functions', array('action'=>'help','users'));?> API section.</p>

<a name="usersearch"></a><div class="section"><h3>Search Users</h3>
<p>Syntax: &nbsp;<tt class="Query">/api/users/search/<span class="P">query</span>/<span class="P">format</span></tt>
<?php echo $html->link('(Full Details)',array('action'=>'help','users#search'));?></p>
<p>Use this API call to search for users.  It accepts a query (composed of one or more words, separated by spaces) and returns a list containing the profiles of all matched users, in a format of your choice.</p>
</div>

<a name="usersearch"></a><div class="section"><h3>Get Profile</h3>
<p>Syntax: &nbsp;<tt class="Query">/api/users/<span class="P">uid</span>/<span class="P">format</span></tt>
<?php echo $html->link('(Full Details)',array('action'=>'help','users#profile'));?></p>
<p>Use this API call get the profile information of a single user.  It acceps an integer user ID (<tt class="P">uid</tt>)and returns the user's profile information in a format of your choice.</p>
</div>

</div><!-- end of Users section -->

<a name="impl"></a><div class="section"><h2>API Clients</h2>
<p>Bindings to the OlinDirectory API are freely available under the <a href="<?php print $this->base;?>/licenses/api/LICENSE.txt">BSD License</a>, meaning you can do just about anything as long as you attribute me.</p>

<a name="impl.py"></a><div class="section"><h3>Python</h3>
<p>Download <a href="<?php print $this->base;?>/code/python/OlinDirectory.py">OlinDirectory.py</a> and put it in the same folder as your Python code.</p>
<p>Sample usage:</p>
<pre class="Output Colorized">
import OlinDirectory
od = OlinDirectory.UserAPI()
 
users = od.findUsers("jeff EH")
for user in users:
    print user
 
users = od.findUsers(classOf=2010, dorm=EH)
for user in users:
    print user.mobilePhone, user.dormShortName, user.dormRoom
 
<span style="color:#090;">#See the source of OlinDirectory.py to see every<br />way you can do searches, and the info you get back</span>
</pre>
</div>

</div><!-- end of Users section -->
