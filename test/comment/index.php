<?php
session_start();
?>
<!DOCTYPE html>
<html>

<head>
    <title>PHP File Upload</title>
</head>
<style>
    div.upload-wrapper {
        color: white;
        font-weight: bold;
        display: flex;
    }

    input[type="file"] {
        position: absolute;
        left: -9999px;
    }

    input[type="submit"] {
        border: 3px solid #555;
        color: white;
        background: #666;
        margin: 10px 0;
        border-radius: 5px;
        font-weight: bold;
        padding: 5px 20px;
        cursor: pointer;
    }

    input[type="submit"]:hover {
        background: #555;
    }

    label[for="file-upload"] {
        padding: 0.7rem;
        display: inline-block;
        background: #fa5200;
        cursor: pointer;
        border: 3px solid #ca3103;
        border-radius: 0 5px 5px 0;
        border-left: 0;
    }

    label[for="file-upload"]:hover {
        background: #ca3103;
    }

    span.file-name {
        padding: 0.7rem 3rem 0.7rem 0.7rem;
        white-space: nowrap;
        overflow: hidden;
        background: #ffb543;
        color: black;
        border: 3px solid #f0980f;
        border-radius: 5px 0 0 5px;
        border-right: 0;
    }
</style>

<body>
    <?php
    if (isset($_SESSION['message']) && $_SESSION['message']) {
        echo '<p class="notification">' . $_SESSION['message'] . '</p>';
        unset($_SESSION['message']);
    }
    ?>
        <div class="upload-wrapper">
            <span class="file-name">Choose a file...</span>
            <label for="file_picker">Browse<input type="file" id="file_picker" name="uploadedFile"></label>
        </div>
        <input type="submit" name="uploadBtn" value="Upload" />
    <script>
        document.addEventListener("DOMContentLoaded", function() {

            const handleImageUpload = (event) => {
                const files = event.target.files;
                const formData = new FormData();
                formData.append("uploadedFile", files[0]);
                console.log(files)
                fetch("upload.php", {
                        method: "POST",
                        body: formData,
                    })
                    .then((response) => response.json())
                    .then((data) => {
                        console.log("File uploaded successfully");
                        console.log(data);
                    })
                    .catch((error) => {
                        console.error(error);
                    });
            };

            document.querySelector("#file_picker").addEventListener("change", (event) => {
                console.log("Uploading file");
                handleImageUpload(event);
            });
        });
    </script>
</body>

</html>