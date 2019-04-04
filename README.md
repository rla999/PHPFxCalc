# PHPFxCalc
This is an ongoing project in my PHP web dev class that I take at my local community college. Starting with lab 4, I will keep all commits in this repo instead of making new repos for each lab where I change or add stuff, since I am now gonna start actively using Github.
To see any versions of this app prior to Lab 4, please consult other repos on my account.

<h2> <strong>Changelog:</strong> </h2>

<h3>Lb 6 (04/03/2019): </h3>
<ul>
  <li>I changed the LoginDataModel and the FxDataModel to allow a database connection to a local MySQL database for storing the users/passwords and the currency information.</li>
  <li>So no more csv file and no more fxUsers.ini for storing acceptable user/password combos.</li>
  </ul>

<h3>Lab 5 (03/28/2019): </h3>
<ul>
  <li>I modified the login and calulcator pages to add security by checking to see if a session already exists for the user and if not, redirecting them back to the login page.</li>
  <li>Also welcomes them on the calculator page!</li>
  <li>No logout button or any mechanism for destroying the session yet unless the user is savvy enough to destroy their browser cookies. :) </li>
  </ul>

<h3>Lab 4 (03/22/2019): </h3>
<ul>
  <li>Added a login functionality as we are introduced to the MVC model. Right now, there is an INI file that stores the parameters for the login form and another INI file that stores the valid user/password combos.</li>
  <li> For the most part, the files related to the calculator itself are unchanged from the midterm lab.</li>
  <li>The login data model handles the validation and the parsing of the INI files.</li>
  <li>If the login fails, the user gets a JavaScript alert telling them that it's an invalid combo or they left fields blank.</li>
  <li>If the login is successful, the user is redirected to the F/X calculator.</li>
  <li><em>Right now there is no way to prevent a savy user from going directly to fxCalc.php and bypassing the login. I will build this out I assume in future labs.</em></li>
  </ul>
