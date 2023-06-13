<?php
require "header.php";
?>
<main>

    <style>
        .hide {
            display: none;
        }
    </style>
    <div class="row">
        <div class="card text-center col-md-4 shadow mx-auto p-0 w-75 mt-3">
            <div class="card-header text-left py-2">
                <h4 class="m-0 font-weight-bold text-primary">post something</h4>
            </div>
            <div class="card-body">

                <div class="row mb-2">
                    <div class="col-4 my-2">
                        <label for="image">
                            <i class="fa fa-image fa-3x"></i>
                        </label>
                        <p>upload image</p>
                    </div>
                </div>
                <div id="dalle-prompt" class="hide">
                    <label class="form-label left" for="textAreaExample">AI Prompt:</label>
                    <textarea class="form-control" id="textAreaExample2" name="dalle-prompt" placeholder="Enter prompt for AI image generation" rows="4"></textarea><br>
                    <button class="dalle-btn btn bg">get image</button>
                </div>
                <form id="form" class="form-check" action="inc/post.inc" method="post" enctype="multipart/form-data">
                    <p id="uplinf"></p>
                    <input type="file" id="image" name="image" class="hide">
                    <input type="hidden" name="type" id="type" value="txt">
                    <input type="hidden" name="dalle" id="dalle-text" value="">
                    <label class="form-label left" for="textAreaExample">text:</label>
                    <textarea class="form-control" id="textAreaExample1" name="posttext" placeholder="enter text for the post" rows="4"></textarea><br>
                    <input class="form-check-input checkbox bg text-left" type="checkbox" name="check" id="check" />
                    <label class="form-check-label text-right" for="check">post to story</label><br>
                    <input type="submit" id="btn" name="upload" class="btn bg col-7 my-2 p-2" value="post">
                </form>
            </div>
        </div>
        <div id="prev" class="card text-center col-md-4 shadow mx-auto hide p-0 mt-3">
            <div class="card-header text-left py-2">
                <button id="cancel" class="btn btn-danger"><i class="fa fa-trash no-h fa-2x"></i></button>
            </div>
            <div class="card-body"><img id="imgprev" style="width: 90%; height: auto;"></div>
        </div>
    </div>

    <?php
    require 'footer.php';
    ?>

    <script>
        let data;
        $('#form').submit(e => {
            // function on submit 
        })
        $('#cancel').click(() => {
            $('#type').val('txt');
            $('#imgprev').attr('src', '');
            $('#prev').hide();
        })

        $('#image').on('change', (e) => {
            $('#type').val('img');
            let property = image.files[0];
            const m = URL.createObjectURL(event.target.files[0]);
            $('#imgprev').attr('src', m);
            $('#prev').show();

        })
    </script>

</main>