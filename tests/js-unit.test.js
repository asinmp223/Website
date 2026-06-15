const assert = require("assert");
const fs = require("fs");
const path = require("path");
const vm = require("vm");

function loadScriptWithMocks() {
  const scriptPath = path.join(__dirname, "..", "script.js");
  const source = fs.readFileSync(scriptPath, "utf8");

  const header = { textContent: "" };
  const button = {
    _listeners: {},
    addEventListener(eventName, callback) {
      this._listeners[eventName] = callback;
    },
  };

  const context = {
    console,
    document: {
      querySelector(selector) {
        if (selector === ".header") {
          return header;
        }
        if (selector === ".btn") {
          return button;
        }
        return null;
      },
    },
  };

  vm.runInNewContext(source, context, { filename: "script.js" });

  return { context, header, button };
}

function testEncodeDecodeRoundTrip() {
  const { context } = loadScriptWithMocks();
  const sample = "Ala ma kota 123!";
  const encoded = context.secret.encode(sample);
  const decoded = context.secret.decode(encoded);

  assert.strictEqual(decoded, sample, "Base64 helper should round-trip plain text");
}

function testClickHandlerDecodesPassword() {
  const { context, header, button } = loadScriptWithMocks();

  assert.strictEqual(typeof button._listeners.click, "function", "Click listener should be registered on .btn");
  button._listeners.click();

  assert.strictEqual(header.textContent, context.secret.decode(context.password), "Click should decode password to header text");
}

function run() {
  testEncodeDecodeRoundTrip();
  testClickHandlerDecodesPassword();
  console.log("JS unit tests passed.");
}

run();
