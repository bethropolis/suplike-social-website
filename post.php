<?php
require 'header.php';
?>
<main>

<style>
    .hide {
        display: none;
    }
</style>
<div class="row">
    <div class="card text-center col-md-4 shadow mx-auto p-0 w-75 mt-3" style="max-height: 410px;"> 
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
               <div class="col-4 my-2" aria-disabled>
                    <label for="music"> 
                        <i class="fa fa-music fa-3x" disabled></i>   
                    </label>
                    <p>upload music</p>
                </div>
             <div class="col-4 my-2" aria-disabled> 
                    <label for="file">
                        <i class="fa fa-file fa-3x" aria-disabled="true"></i>
                    </label>
                    <p>upload file</p>
                </div>                
            </div>

            <form id="form" action="./inc/post.inc.php" method="post" enctype="multipart/form-data"> 
                <p id="uplinf"></p> 
                <input type="file" id="image" name="image" class="hide">
                <input type="hidden" name="type" id="type" value="txt"> 
                <label class="col-12 text-left">text:
                    <textarea id="txt" class="col-12 p-2"  name="posttext" placeholder="enter text for the post"></textarea> </label>
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
       
       let image_name = property.name;
       let img_ext = image_name.split('.').pop().toLowerCase();
       let img_size = property.size;  

    })
 
   $('#btn').click(() => {
       $.post('./test/post/post.test.php',{
           image: data, 
           text: $('#txt').text(),          
       },function(dat){
              $('#uplinf').text('UPLOADED✔')
               console.log(dat)  
       })     
   })
</script>

</main>