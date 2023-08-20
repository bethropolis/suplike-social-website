<?php
require "header.php";
$tag = '';
if (isset($_GET['tag'])) {
    $tag = $_GET['tag'];
}
?>
<?php
if (isset($_GET['id'])) {
?>
    <link rel="stylesheet" href="css/post.min.css?v.1">

<?php } ?>


<main class="post-page">

    <style>
        .hide {
            display: none;
        }
        .post-div{
            width: 100%;
        }
    </style>
    <?php
    if (!isset($_GET['id'])) {
    ?>
        <div class="card col-lg-9 mx-auto co">
            <div class="card-body">
                <h5 class="card-title">Create Post</h5>
                <div id="toast"></div>
                <form id="postForm" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="communitySelect">Post to</label>
                        <select class="form-select" id="communitySelect" name="community">
                            <option value="account"  <?= isset($_GET['story']) ?: "selected" ?>>Your page</option> <!-- added a default option -->
                            <option value="story" <?= !isset($_GET['story']) ?: "selected" ?>>Your story</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <a href="" data-lightbox="index">
                            <img id="previewImage" class="hide img-thumbnail" src="#" alt="Preview Image" loading="lazy" style="max-height: 152px; max-width: 200px;">
                        </a>
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
                        <textarea class="form-control" id="postText" name="postText" placeholder="Enter some text for your post"></textarea> <!-- added a placeholder -->
                    </div>

                    <details class="mb-1" <?= $tag ? 'open' : '' ?>>
                        <summary>Add Tags</summary>
                        <div class="mb-3">
                            <label for="tagsInput">Tags</label>
                            <input type="text" class="form-control" id="tagsInput" name="tags" value="<?= $tag ?>" placeholder="tag1,tag2,tag3">
                        </div>
                    </details>

                    <div class="mb-3" hidden>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                    </div>

                    <input type="submit" id="upload" class="btn bg" name="upload" value="Submit" />
                </form>
            </div>
        </div>
    <?php } ?>
    <div id="main-post">
        <noscript style="color:red">this site requires javascript to function</noscript>
    </div>
</main>

<script src="./lib/lightbox/js/lightbox.min.js" defer></script>
<?php
require 'footer.php';
?>
<script>
    $(document).ready(function() {

        <?php
        if (isset($_GET['id'])) {
        ?>
            let post_url = './inc/post.inc.php?id=' + '<?= $_GET['id'] ?>&';
            mainload(post_url)
        <?php } ?>

        // Preview image on file selection
        $("#image").change(function() {
            if (this.files && this.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $("#previewImage").attr("src", e.target.result).removeClass("hide");
                    $("[data-lightbox]").attr("href", e.target.result);
                    $(".fa-trash").removeClass("hide");
                };
                reader.readAsDataURL(this.files[0]);
            }
        });

        // Handle form submission
        $("#postForm").submit(function(e) {
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
                success: function(response) {
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
                error: function(xhr, status, error) {
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
            $("#toast").fadeOut(5000, function() {
                $(this).empty().show();
            });
        }


        function clearImage() {
            $("#previewImage").attr("src", '').addClass('hide');
            $("[data-lightbox]").attr("href", '');
            $(".fa-trash").addClass("hide");
            $('#image').val('');
        }

        $('.fa-trash').click(function() {
            clearImage();
        });

    });
</script>