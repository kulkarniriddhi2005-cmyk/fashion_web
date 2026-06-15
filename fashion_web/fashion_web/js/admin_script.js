const userBtn = document.querySelector('#user-btn');
if (userBtn) {  // Check if the element exists
    userBtn.addEventListener('click', function () {
        const userBox = document.querySelector('.profile');
        if (userBox) {  // Ensure userBox exists
            userBox.classList.toggle('active');
        }
    });
}   

// const toggle = document.querySelector('.toggle-btn');
// toggle.addEventListener('click',function(){
//     const sidebar = document.querySelector('.sidebar');
//     sidebar.classList.toggle('active');
// })
const toggle = document.querySelector('.toggle-btn');
if (toggle) {  // Check if the element exists
    toggle.addEventListener('click', function () {
        const sidebar = document.querySelector('.sidebar');
        if (sidebar) { 
            sidebar.classList.toggle('active');
        }
    });
}

//----------------------changing photos in read product page as you click to another photo--------------//
let thumbnails = document.getElementsByClassName('slider-thumbnail');
let activeImages = document.getElementsByClassName('active');

for(var i=0;i<thumbnails.length;i++){
    thumbnails[i].addEventListener('click',function(e){
        e.preventDefault()
        if(activeImages.length>0){
            activeImages[0].classList.remove('active');
        }
        this.classList.add('active');
        document.getElementById('featuredImage').src = this.href;
    })
}
