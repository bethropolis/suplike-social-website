<?php
require "header.php";
?>



<main>

    <style>
        .hide {
            display: none;
        }
    </style>

    <div class="card co">
        <div class="card-body">
            <h5 class="card-title">Create Post</h5>
            <div id="toast"></div>
            <form id="postForm" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="communitySelect">Post to</label>
                    <select class="form-select" id="communitySelect" name="community">
                        <option value="account" selected>Your page</option> <!-- added a default option -->
                        <option value="story">Your story</option>
                    </select>
                </div>

                <div class="mb-3">
                    <img id="previewImage" class="hide img-thumbnail" src="#" alt="Preview Image"
                        style="max-height: 152px; max-width: 200px;">
                    <i class="fa fa-trash hide"></i>
                </div>
                <div class="row my-2 col-sm-6">
                    <div class="col-4 my-2">
                        <label for="image">
                            <i class="fa fa-image fa-2x"></i>
                        </label>
                        <p>image</p>
                    </div>

                </div>

                <div class="mb-3">
                    <label for="postText">Post Text</label>
                    <textarea class="form-control" id="postText" name="postText"
                        placeholder="Enter some text for your post"></textarea> <!-- added a placeholder -->
                </div>

                <details class="mb-1">
                    <summary>Add Tags</summary>
                    <div class="mb-3">
                        <label for="tagsInput">Tags</label>
                        <input type="text" class="form-control" id="tagsInput" name="tags" placeholder="tag1,tag2,tag3">
                    </div>
                </details>

                <div class="mb-3" hidden>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                </div>

                <input type="submit" id="upload" class="btn bg" name="upload" value="Submit" />
            </form>
        </div>
    </div>

</main>

<?php
require 'footer.php';
?>

<script>
    $(document).ready(function () {
        // Preview image on file selection
        $("#image").change(function () {
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $("#previewImage").attr("src", e.target.result).removeClass("hide");
                    $(".fa-trash").removeClass("hide");
                };
                reader.readAsDataURL(this.files[0]);
            }
        });

        // Handle form submission
        $("#postForm").submit(function (e) {
            e.preventDefault();


            // Get form data
            var formData = new FormData(this);
            formData.append("type", $("#image").get(0).files.length > 0 ? "img" : "txt");
            formData.append("upload", 'post');

            // Submit form using AJAX
            $.ajax({
                url: "./inc/post.inc.php",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    // Handle success response
                    if (response.type == 'success') {
                        // Show success toast
                        showToast("Success!", "Your post has been created.", "success");

                        // Clear fields and image
                        $("#previewImage").attr("src", '').addClass('hide');
                        $(".fa-trash").addClass("hide");
                        $('#postText').val('');
                        $('#tagsInput').val('');
                        $('#image').val('');
                    } else {
                        // Show error toast
                        showToast("Error!", `error: ${response.message || response.msg}`, "warning");
                    }
                },
                error: function (xhr, status, error) {
                    // Handle error
                    console.error(error);

                    // Show error toast
                    showToast("Error!", "An error occurred while creating your post.", "warning");
                }
            });
        });

        // Function to display a toast notification
        function showToast(title, message, type) {
            let toast = `<div class="toast" role="alert" aria-live="assertive" aria-atomic="true">
                        <div class="toast-body mb-3 p-2 rounded text-white bg-${type}">
                            ${message}
                        </div>
                        
                    </div>`;

            $("#toast").html(toast);
            $("#toast").fadeOut(2000, function () {
                $(this).empty().show();
            });

        }


        function clearImage() {
            $("#previewImage").attr("src", '').addClass('hide');
            $(".fa-trash").addClass("hide");
            $('#image').val('');
        }

        $('.fa-trash').click(function () {
            clearImage();
        });

    });

</script>