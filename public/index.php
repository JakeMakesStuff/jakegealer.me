<?php
    // Grabs the YML file with all of the portfolio information.
    $portfolio_yml = yaml_parse_file("portfolio.yml");
?>
<!DOCTYPE HTML>
<html lang="en">
    <head>
        <title><?php echo $portfolio_yml['name'] ?></title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="keywords" content="Jake Gealer,Developer,HTML,Python,Javascript,TypeScript">
        <meta name="author" content="<?php echo $portfolio_yml['name'] ?>">
        <meta name="description" content="<?php echo $portfolio_yml['description'] ?>">
        <meta property="og:type" content="website">
        <meta property="og:title" content="<?php echo $portfolio_yml['name'] ?>">
        <meta property="og:url" content="<?php echo $portfolio_yml['url'] ?>">
        <meta property="og:description" content="<?php echo $portfolio_yml['description'] ?>">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bulma/0.9.1/css/bulma.min.css">
        <script src="https://hcaptcha.com/1/api.js" async defer></script>
    </head>

    <body>
        <div id="resultModal" class="modal">
            <div class="modal-background"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title" id="resultTitle"></p>
                    <button class="delete" aria-label="close" onclick="closeModals()"></button>
                </header>
                <section class="modal-card-body">
                    <span id="resultDescription"></span>
                </section>
            </div>
        </div>

        <div id="contactForm" class="modal">
            <div class="modal-background"></div>
            <div class="modal-card">
                <header class="modal-card-head">
                    <p class="modal-card-title">Contact Me</p>
                    <button class="delete" aria-label="close" onclick="closeModals()"></button>
                </header>
                <form onsubmit="formSubmit()">
                    <section class="modal-card-body">
                        <p><?php echo $portfolio_yml["contact_message"] ?></p>
                        <div class="field">
                            <label class="label">Name:</label>
                            <div class="control">
                                <div class="control">
                                    <input class="input" type="text" id="formName" oninput="validateForm()" placeholder="Name">
                                </div>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">E-mail Address:</label>
                            <div class="control">
                                <div class="control">
                                    <input class="input" type="text" id="formEmail" oninput="validateForm()" placeholder="E-mail Address">
                                </div>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">Description:</label>
                            <div class="control">
                                <div class="control">
                                    <textarea class="textarea" id="formDescription" oninput="validateForm()" placeholder="Desciption"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="field">
                            <label class="label">CAPTCHA:</label>
                            <div class="control">
                                <div class="h-captcha" data-sitekey="<?php echo $portfolio_yml["hcaptcha_site_key"] ?>" data-callback="hcaptchaResultSet"></div>
                            </div>
                        </div>
                    </section>
                    <footer class="modal-card-foot">
                        <button type="submit" class="button is-success is-disabled" id="formButton">Submit</button>
                    </footer>
                </form>
            </div>
        </div>

        <div class="container" style="padding: 2em">
            <div style="padding: 1em; text-align: center">
                <h1 class="title"><?php echo $portfolio_yml['name'] ?></h1>
                <h2 class="subtitle"><?php echo $portfolio_yml['description'] ?></h2>
                <?php
                    if ($portfolio_yml['enable_contact']) {
                        echo '<a class="button is-link" href="javascript:openForm()" style="margin-right: 5px">Contact</a>';
                    }
                    foreach ($portfolio_yml['buttons'] as &$button) {
                        echo(sprintf('<a class="button is-link" href="%s" style="margin-right: 5px">%s</a>', $button['url'], $button['name']));
                    }
                ?>
            </div>
            <?php
            if (array_key_exists("work", $portfolio_yml)) {
                echo("<hr><h1 class=\"title\">Experience</h1><p>This is my experience when it comes to work. If you have any questions, do not hesitate to contact me:</p>");
                foreach ($portfolio_yml["work"] as $job => $additional_info) {
                    $left = "present";
                    if ($additional_info["left"]) {
                        $left = $additional_info["left"];
                    }
                    echo("<hr>");

                    $btn = sprintf('<a class="button is-link" href="%s"> Learn more about %s</a>', $additional_info['url'], $job);
                    echo('<div class="columns"><div class="column">'. "<h2 class=\"subtitle\"><a href=\"". $additional_info["url"] . "\">" . $job . " (" .  $additional_info["started"] . "-" . $left . ")</a></h2>" . $additional_info['description'] . '<br><br>' . $btn .  '</div>');
                    echo('<div class="column is-one-fifth is-hidden-mobile" style="width: 10%"><br><img src="img/' . $additional_info['icon'] . '" alt="Logo" style="margin: 0"></div>' .'</div>');
                }
            }
            ?>
            <hr>
            <div class="columns">
                <div class="column">
                    <h1 class="title">Education</h1>
                    <p>These are the places where I have been educated and the qualifications I have got while I was there. If you have any questions, do not hesitate to contact me:</p>
                    <hr>
                    <?php
                    foreach ($portfolio_yml['qualifications'] as $school => $additional_info) {
                        echo(sprintf('<h2 class="subtitle"><a href="%s">%s</a></h2>', $additional_info['url'], $school));
                        $p_part = "<p><b>I started at " . $school . " in " . $additional_info['started'];
                        if ($additional_info['graduated'] != null) {
                            $p_part .= " and graduated in " . $additional_info['graduated'] . ":</b></p>";
                        } else {
                            $p_part .= ":</b></p>";
                        }
                        echo $p_part . "<br>";
                        foreach ($additional_info['grades'] as $grade_name => $grade) {
                            if ($grade == null) {
                                $grade = "Currently in progress";
                            }
                            echo "<p>" . $grade_name . ": " . $grade . "</p>";
                        }
                        echo '<hr>';
                    }
                    ?>
                </div>
                <div class="column">
                    <h1 class="title">Projects</h1>
                    <p>Here are some projects which I have had a part in. If you have any questions, do not hesitate to contact me:</p>
                    <hr>
                    <?php
                    foreach ($portfolio_yml['projects'] as $project => $additional_info) {
                        echo(sprintf('<h2 class="subtitle"><a href="%s">%s</a></h2>', $additional_info['url'], $project));
                        echo('<div class="columns"><div class="column is-four-fifths"><p>' . $additional_info['description'] . '</p><br>' . sprintf('<a class="button is-link" href="%s"> Learn more about %s</a>', $additional_info['url'], $project) .  '</div>');
                        echo('<div class="column is-hidden-mobile"><img src="img/' . $additional_info['icon'] . '" alt="Logo" style="margin: 0"></div>' .'</div><hr>');
                    }
                    ?>
                </div>
            </div>
        </div>
        <footer class="footer" style="padding: 20px">
            <div class="content has-text-centered">
                <p>
                    Website template/renderer by <a href="https://jakegealer.me">Jake Gealer</a>. The source code for this website is licensed under the <a href="https://opensource.org/licenses/MPL-2.0">MPL-2.0</a> license.
                </p>
            </div>
        </footer>

        <script>
            // Get the various elements we need to manage.
            var resultModal = document.getElementById("resultModal");
            var resultTitle = document.getElementById("resultTitle");
            var resultDescription = document.getElementById("resultDescription");
            var contactForm = document.getElementById("contactForm");
            var formButton = document.getElementById("formButton");
            var formEmail = document.getElementById("formEmail");
            var formName = document.getElementById("formName");
            var formDescription = document.getElementById("formDescription");

            // Used to set the buttons state.
            function setButtonState(disabled) {
                if (disabled) formButton.classList.add("is-disabled");
                else formButton.classList.remove("is-disabled");
            }

            // Validates the form.
            function validateForm() {
                setButtonState(formEmail.input === "" || formName.input === "" || formDescription.input === "" || hcaptchaResult === null);
            }

            // Used to get/set the hCAPTCHA result.
            var hcaptchaResult = null;
            function hcaptchaResultSet(datakey) {
                hcaptchaResult = datakey;
                validateForm();
            }

            // Opens the result modal.
            function openResult(title, description) {
                contactForm.classList.remove("is-active");
                resultTitle.innerText = title;
                resultDescription.innerText = description;
                resultModal.classList.add("is-active");
            }

            // Closes all modals.
            function closeModals() {
                resultModal.classList.remove("is-active");
                contactForm.classList.remove("is-active");
            }

            // Handle the form being submitted.
            function formSubmit() {
                if (hcaptchaResult === null) {
                    // Tell the user to fill out the hCaptcha.
                    openResult("hCaptcha Blank", "You did not fill out the hCaptcha.");
                } else {
                    // Mark the button as loading.
                    formButton.classList.add("is-loading");

                    // Create a HTTP request.
                    var xhttp = new XMLHttpRequest();
                    xhttp.onreadystatechange = function() {
                        if (this.readyState === 4) {
                            if (this.status === 204) {
                                // Success!
                                openResult("Form Submission Successful", "I have successfully received your message!");
                            } else if (this.status === 400) {
                                // This was us.
                                openResult("Form Submission Error", xhttp.responseText);
                            } else {
                                // Cloudflare or the mailing service is having a bad day.
                                openResult("Form Submission Error", "There was an error submitting the form.");
                            }
                        }

                        // Remove loading from the form and reset hCaptcha.
                        hcaptcha.reset();
                        formButton.classList.remove("is-loading");
                    };
                    xhttp.open("POST", "/v1/submit", true);
                    xhttp.setRequestHeader("Content-Type", "application/json");
                    xhttp.send(JSON.stringify({"name": formName.input, "description": formDescription.input, "email": formEmail.input}));
                }

                // Prevent standard form behaviour.
                return false;
            }

            // Opens the contact form.
            function openForm() {
                contactForm.classList.add("is-active");
            }
        </script>
    </body>
</html>
