# PHPFxCalc
This is an ongoing project in my PHP web dev class that I take at my local community college. Starting with lab 4, I will keep all commits in this repo instead of making new repos for each lab where I change or add stuff, since I am now gonna start actively using Github.

<h2> <strong>Changelog:</strong> </h2>

<h3>Lab 4 (03/22/2019): </h3>
<ul>
  <li>Added a login functionality as we are introduced to the MVC model. Right now, there is an INI file that stores the parameters for the login form and another INI file that stores the valid user/password combos.</li>
  <li> For the most part, the files related to the calculator itself are unchanged from the midterm lab.</li>
  <li>The login data model handles the validation and the parsing of the INI files.</li>
  <li>If the login fails, the user gets a JavaScript alert telling them that it's an invalid combo or they left fields blank.</li>
  <li>If the login is successful, the user is redirected to the F/X calculator.</li>
  <li><em>Right now there is no way to prevent a savy user from going directly to fxCalc.php and bypassing the login. I will build this out I assume in future labs.</em></li>
  </ul>
