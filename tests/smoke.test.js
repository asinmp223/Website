const assert = require("assert");
const fs = require("fs");
const path = require("path");

function read(fileName) {
  const filePath = path.join(__dirname, "..", fileName);
  return fs.readFileSync(filePath, "utf8");
}

function testScriptContainsExpectedPasswordSource() {
  const script = read("script.js");
  assert(script.includes('var password = "VGgxNV8xNV81VFIwbjY";'), "Expected encoded password constant in script.js");
  assert(script.includes("header.textContent = secret.decode(password);"), "Expected click handler to decode password");
}

function testFormPostsRequiredVariables() {
  const form = read("pass_form.html");
  assert(form.includes('action="pass_accept.php"'), "Form should post to pass_accept.php");
  assert(form.includes('name="pws"'), "Form should include pws field");
  assert(form.includes('name="srt"'), "Form should include srt field");
  assert(form.includes('name="fName"'), "Form should include fName field");
}

function testPhpValidatesRequestMethod() {
  const php = read("pass_accept.php");
  assert(php.includes('$_SERVER["REQUEST_METHOD"] === "POST"'), "PHP should validate request method");
  assert(php.includes('htmlspecialchars($firstName, ENT_QUOTES, "UTF-8")'), "PHP should escape user output");
}

function run() {
  testScriptContainsExpectedPasswordSource();
  testFormPostsRequiredVariables();
  testPhpValidatesRequestMethod();
  console.log("Smoke tests passed.");
}

run();
