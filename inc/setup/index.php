<?php
$setup = json_decode(file_get_contents('./setup.suplike.json'));
if ($setup->setup) {
    echo "<h1>Already set up</h1>";
    echo "<a href='../../login.php'>go to login page</a><br/><br/>";
    die("need any <a href='https://github.com/bethropolis/suplike-social-website/discussion'>help</a>?");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <link rel="shortcut icon" href="../../img/icon/favicon.ico" type="image/x-icon">
    <title>Suplike Setup</title>
    <link rel="stylesheet" href="../../lib/font-awesome/css/all.min.css" />
    <link rel="stylesheet" href="../../lib/bootstrap/css/bootstrap.min.css" />
    <style>
        body {
            margin: 0;
            font-family: Roboto, sans-serif;
            background-color: #f8f8f8;
        }

        form {
            position: relative;
            top: 20px;
            margin: auto;
            background: white;
            padding: 20px;
            width: 70%;
            display: grid;
            grid-gap: 10px;
            grid-template-columns: 1fr;
            justify-content: center;
            margin: 0 auto;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        input,
        details {
            padding: 12px;
            border: none;
            margin: auto;
            font-size: 1.1em;
            line-height: 1.5;
        }

        details {
            width: 90%;
        }

        input:not(type="checkbox") {
            width: 90%;
            border-radius: 3px;
            font-weight: 700;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            background-color: #f8f8f8;
        }

        .submit {
            padding: 10px;
            background-color: #8d55e8;
            color: white;
            font-size: 1.3em;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .submit:hover {
            opacity: 0.8;
        }

        h3 {
            margin: 0;
            font-size: 1.2em;
            font-weight: 700;
            color: #8d55e8;
        }
    </style>
</head>

<body>
    <h2 class="text-center mt-1" style="font-family: 'Roboto', sans-serif; color: #8d55e8; font-weight: bold; font-size: 2em;">SUPLIKE SETUP</h2>
    <form id="setup-form">
        <div id="error-container" class="alert alert-danger text-center" style="display: none;">
            <h5 id="error-message" class="text-danger">error</h5>
        </div>
        <h3>Enter database credentials (MYSQL, MariaDB)</h3>

        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="fas fa-database"></i>
                </span>
            </div>
            <input type="text" class="form-control" placeholder="Server name... (e.g., localhost)" value="" name="server" required data-toggle="tooltip" data-placement="top" title="Enter the server name or IP address where your database is hosted." />
        </div>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="fas fa-user"></i>
                </span>
            </div>
            <input type="text" class="form-control" placeholder="Database Username..." value='' name="name" data-toggle="tooltip" data-placement="top" title="Enter the username for your database." />
        </div>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="fas fa-lock"></i>
                </span>
            </div>
            <input type="password" class="form-control" placeholder="Database Password..." title="Leave empty if none" name="pwd" data-toggle="tooltip" data-placement="top" title="Enter the password for your database." />
        </div>
        <details>
            <summary>
                Advanced (Optional)
            </summary>

            <div class="input-group">
                <div class="input-group-prepend">
                    <span class="input-group-text">
                        <i class="fas fa-database"></i>
                    </span>
                </div>
                <input type="text" class="form-control" name="db" placeholder="Database name... (default: suplike)" data-toggle="tooltip" data-placement="top" title="Enter the name of the database to use. If not provided, the default name 'suplike' will be used." />
            </div>
            <div class="form-group">
                <div class="form-check mt-2">
                    <input type="checkbox" class="form-check-input" name="drop" id="drop" data-toggle="tooltip" data-placement="top" title="please note that this deletes any pre-existing data on the database" />
                    <label class="form-check-label" for="drop">Drop database before running SQL</label>
                </div>
            </div>

        </details>
        <h3>Create admin account</h3>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="fas fa-user"></i>
                </span>
            </div>
            <input type="text" class="form-control" name="user" placeholder="Username..." required data-toggle="tooltip" data-placement="top" title="Enter the desired username for the admin account." />
        </div>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="fas fa-envelope"></i>
                </span>
            </div>
            <input type="email" class="form-control" name="mail" placeholder="Email..." data-toggle="tooltip" data-placement="top" title="Enter the email address for the admin account (optional)." />
        </div>
        <div class="input-group">
            <div class="input-group-prepend">
                <span class="input-group-text">
                    <i class="fas fa-lock"></i>
                </span>
            </div>
            <input type="password" class="form-control" name="pass" placeholder="Password..." required data-toggle="tooltip" data-placement="top" title="Enter the desired password for the admin account." />
        </div>
        <button type="submit" class="submit w-25 mx-auto">Submit <i id="loading" class="fas fa-spinner fa-spin" style="display: none"></i>  </button>
    </form>


    <!-- Bootstrap and jQuery scripts -->
    <script src="../../lib/jquery/jquery.js"></script>
    <script src="../../lib/sweetalert/sweetalert.js"></script>
    <script src="../../lib/bootstrap/js/bootstrap.bundle.min.js"></script>


    <script>
        $(document).ready(function() {
            const form = $('#setup-form');

            form.submit(function(event) {
                event.preventDefault(); // Prevent the default form submission
                $('#loading').show();
                $('#error-container').hide();

                const formData = new FormData(this);
                const apiEndpoint = './setup.inc.php'; // Replace with your API endpoint URL

                $.ajax({
                    url: apiEndpoint,
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    processData: false,
                    contentType: false,
                    success: function(data) {
                        // Handle the API response
                        if (data.success) {
                            // display a sweetalert then load to the login page

                        } else {
                            // Handle setup errors
                            displayErrorMessage(data.msg);
                        }

                        $('#loading').hide();
                    },
                    error: function() {
                        // Handle fetch or network error
                        displayErrorMessage('An error occurred. Please try again later.');
                        $('#loading').hide();
                    }
                });
            });

            function displayErrorMessage(message) {
                $('#error-message').text(message);
                $('#error-container').show();
            }

            // <!-- Tooltip initialization -->
            $(function() {
                $('[data-toggle="tooltip"]').tooltip();
            });

        });
    </script>

    <!-- GitHub icon -->
    <a href="https://github.com/bethropolis/suplike-social-website" target="_blank" rel="noopener noreferrer" style="color:#8d55e8;">
        <i class="fab fa-github" style="position: fixed; bottom: 20px; right: 20px; font-size: 32px;" title="get some help from the github repo"></i>
    </a>
</body>

</html>