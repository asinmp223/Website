<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>HackerU</title>
  <!-- Bootstrap core CSS -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom fonts for this template -->
  <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
  <link href='https://fonts.googleapis.com/css?family=Lora:400,700,400italic,700italic' rel='stylesheet' type='text/css'>
  <link href='https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800' rel='stylesheet' type='text/css'>
  <!-- Custom styles for this template -->
  <link href="css/clean-blog.min.css" rel="stylesheet">
</head>

<body style="background-image: url('img/background.jfif'); background-size:100% 100%">
  <!-- Page Header -->
  <div class="container py-5">
      <div class="col-12 col-md-8 col-lg-6 mx-auto">
          <div style="background-color: rgb(255,255,255,0.4)" class="p-3 shadow">
            <?php
                                $password = $_POST["pws"] ?? "";
                                $secret = $_POST["srt"] ?? "";
                                $firstName = trim($_POST["fName"] ?? "");

                                $isValidRequest = $_SERVER["REQUEST_METHOD"] === "POST"
                                    && $password === base64_decode("VGgxNV8xNV81VFIwbjY")
                                    && $secret === "1352"
                                    && $firstName !== "";

                                if ($isValidRequest) {
                                    echo '<h3 class="text-success">Welcome, ' . htmlspecialchars($firstName, ENT_QUOTES, "UTF-8") . '</h3>';
                                    echo '<p class="mb-3">Validation passed. JavaScript challenge loaded.</p>';
                                    echo '<div id="d1" style="text-align:center;font-family:fantasy;"><div>Block in d1</div></div>';
                                    echo '<div id="d2"><h1 id="h" style="color:tomato;transform:scale(-1);">Challenge Header</h1></div>';
                                    echo '<div id="mathTarget" class="mt-3 p-2 border bg-light">Click here to trigger A2()</div>';
                                    echo '<script>
                                        var div_1 = document.querySelector("#d1");
                                        var div_2 = document.querySelector("#d2");
                                        var header = document.querySelector("#h");

                                        var div_style = window.getComputedStyle(div_1);
                                        var header_style = window.getComputedStyle(header);

                                        function A1() {
                                            if (div_1.children[0].nodeName === "DIV") {
                                                console.log("You nailed it !");
                                                if (div_2.children[0].nodeName === "H1") {
                                                    div_2.children[0].textContent = "This is correct too!";
                                                    A3();
                                                }
                                            }
                                        }

                                        function A2(params) {
                                            if ((params / (10 % 4)) === 132.993) {
                                                var m = document.querySelector("#mathTarget");
                                                if (m) {
                                                    m.textContent = "Set Text In Here :";
                                                }
                                            }
                                        }

                                        function A3() {
                                            if (div_style.textAlign === "center" && div_style.fontFamily === "fantasy") {
                                                console.log("Just one more step");
                                                if (header_style.color === "rgb(255, 99, 71)" && header_style.transform === "matrix(-1, 0, 0, -1, 0, 0)") {
                                                    alert("AMAZING YOU DID IT !!!");
                                                }
                                            }
                                        }

                                        var target = document.querySelector("#mathTarget");
                                        if (target) {
                                            target.addEventListener("click", function () {
                                                A2(265.986);
                                            });
                                        }

                                        A1();
                                    </script>';
                                } else {
                                    echo '<h3 class="text-danger">Access denied</h3>';
                                    echo '<p>Required values were not provided or invalid.</p>';
                                    echo '<a href="pass_form.html" class="btn btn-primary btn-sm">Go back to form</a>';
                                }
                ?>
          </div>
      </div>      
  </div>

</body>

</html>