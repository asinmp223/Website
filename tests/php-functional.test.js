const assert = require("assert");
const { spawnSync } = require("child_process");
const path = require("path");

function renderPassAccept(postData, requestMethod) {
  const workspacePath = path.join(__dirname, "..");
  const phpCode = `
    $payload = json_decode($argv[1], true);
    $_SERVER["REQUEST_METHOD"] = $argv[2];
    $_POST = $payload;
    ob_start();
    include "pass_accept.php";
    $output = ob_get_clean();
    echo $output;
  `;

  const result = spawnSync("php", ["-r", phpCode, JSON.stringify(postData), requestMethod], {
    cwd: workspacePath,
    encoding: "utf8",
  });

  if (result.error) {
    throw new Error(`PHP runtime error: ${result.error.message}`);
  }

  if (result.status !== 0) {
    throw new Error(`PHP exited with code ${result.status}: ${result.stderr || result.stdout}`);
  }

  return result.stdout;
}

function testValidRequestShowsWelcome() {
  const html = renderPassAccept(
    {
      pws: "Th15_15_5TR0n6",
      srt: "1352",
      fName: "Jan",
    },
    "POST"
  );

  assert(html.includes("Welcome, Jan"), "Valid payload should render welcome message");
  assert(html.includes("Validation passed"), "Valid payload should render success text");
}

function testInvalidSecretDeniesAccess() {
  const html = renderPassAccept(
    {
      pws: "Th15_15_5TR0n6",
      srt: "wrong",
      fName: "Jan",
    },
    "POST"
  );

  assert(html.includes("Access denied"), "Invalid secret should deny access");
}

function testXssPayloadInFirstNameIsRejected() {
  const html = renderPassAccept(
    {
      pws: "Th15_15_5TR0n6",
      srt: "1352",
      fName: "<script>alert(1)</script>",
    },
    "POST"
  );

  assert(html.includes("Access denied"), "XSS payload should be rejected by server-side validation");
}

function run() {
  testValidRequestShowsWelcome();
  testInvalidSecretDeniesAccess();
  testXssPayloadInFirstNameIsRejected();
  console.log("PHP functional tests passed.");
}

run();
