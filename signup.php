<?php

require "header.php"; 


?> 

 <main>
   <div class="center">  
    <h1>sign up</h1>  
    <form class="form mx-auto" action="inc/signup.inc.php"  method="post" enctype="multipart/form-data">        
     <label for="profile-pic" id="profile-pic-label"   style="background:url('img/M.jpg'); background-size: cover; width: 70px;height: 70px; border-radius: 50%;"><i class="fa fa-edit-o fa-2x"></i></label>
     <input type="file" name="profile-pic" id="profile-pic"  style="display: none;"> <br/>   
     <label for="uid">username:</label><br/> 
     <input type="text" id="uid" name="uid" title="enter your username" placeholder="username..." autofocus required><br/> 
     <label for="mail">email:</label><br/>   
     <input type="email" id="mail" name="mail" title="enter your email" placeholder="email..."  required><br/>
      <label for="fn">firstname:</label><br/>  
     <input type="text" id="fn" name="firstname" title="enter your firstname" placeholder="firstname.." ><br/> 
      <label for="fn">lastname:</label><br/>
     <input type="text" id="ln" name="lastname" title="enter your  lastname" placeholder="lastname..."><br/>  
      <label for="fn">pwd:</label><br/>
     <input type="password" id="pwd" name="pwd" title="enter your password"  placeholder="password..." required><br/>
      <label for="fn">pwdR:</label><br/>
     <input type="password" id="pwdR" name="pwd-repeat" title="repeat password" placeholder="repeat-password..."><br/>
      <label for="fn">birth date:</label><br/> 
     <input type="date" id="age" name="age" title="enter your birthday"><br/><br/>   
     <button type="submit" class="btn bg" style="height: 45px; width: 140px;" name="signup-submit">signup</button><br/>       
    </form> 
    <h5 class="my-1">already have an account?<a href="./login.php" style="color: var(--ac);">login</a></h5>       
   </div>
</main> 
 <?php

require "footer.php" 
 ?>

  <script>
    $(document).ready(function(){
     var c = document.querySelector('#profile-pic');  
     c.addEventListener('change', function (e) {     
       e.preventDefault();
        var m = URL.createObjectURL(event.target.files[0]);
       $('#profile-pic-label').css({"background": "url("+m+")"});      
      });
   });
 </script>



 <!-- 
<style type="text/css">
  html, body {
   background-color: #996bfd; 
   display: flex;
   align-items: center;
   justify-content: center;
   height: 100%;
   position: relative;
}
</style>




<script type="text/javascript">
class ProgressRing extends HTMLElement {
  constructor() {
    super();
    const stroke = this.getAttribute('stroke');
    const radius = this.getAttribute('radius');
    const normalizedRadius = radius - stroke * 2;
    this._circumference = normalizedRadius * 2 * Math.PI;

    this._root = this.attachShadow({mode: 'open'});
    this._root.innerHTML = `
      <svg
        height="${radius * 2}"
        width="${radius * 2}"
       >
         <circle
           stroke="white"
           stroke-dasharray="${this._circumference} ${this._circumference}"
           style="stroke-dashoffset:${this._circumference}"
           stroke-width="${stroke}"
           fill="transparent"
           r="${normalizedRadius}"
           cx="${radius}"
           cy="${radius}"
        />
      </svg>

      <style>
        circle {
          transition: stroke-dashoffset 0.35s;
          transform: rotate(-90deg);
          transform-origin: 50% 50%;
        }
      </style>
    `;
  }
  
  setProgress(percent) {
    const offset = this._circumference - (percent / 100 * this._circumference);
    const circle = this._root.querySelector('circle');
    circle.style.strokeDashoffset = offset; 
  }

  static get observedAttributes() {
    return ['progress'];
  }

  attributeChangedCallback(name, oldValue, newValue) {
    if (name === 'progress') {
      this.setProgress(newValue);
    }
  }
}

window.customElements.define('progress-ring', ProgressRing);

// emulate progress attribute change
let progress = 0;
const el = document.querySelector('progress-ring');

const interval = setInterval(() => {
  progress += 10;
  el.setAttribute('progress', progress);
  if (progress === 100)
    clearInterval(interval);
}, 1000);
</script> -->