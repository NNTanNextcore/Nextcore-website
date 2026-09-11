





$ = jQuery;
callApiGetPost()
function callApiGetPost() {
    
    $.ajax({
        url: ajax_obj.ajax_url,
        type: 'POST',
        data: {
            action: 'get_image_detai_post'
        },
        success: function (response) {
            show_image(response.data);
        },
        error: function (error) {
            console.log('Error');
        }
    });
    
}

function show_image($data) {
    const lgContainer = document.getElementById("inline-gallery-container");

if (lgContainer) {

    const inlineGallery = lightGallery(lgContainer, {
      container: lgContainer,
      dynamic: true,
      // Turn off hash plugin in case if you are using it
      // as we don't want to change the url on slide change
      hash: false,
      // Do not allow users to close the gallery
      closable: false,
      // Add maximize icon to enlarge the gallery
      showMaximizeIcon: true,
      // Append caption inside the slide item
      // to apply some animation for the captions (Optional)
      appendSubHtmlTo: ".lg-item",
      // Delay slide transition to complete captions animations
      // before navigating to different slides (Optional)
      // You can find caption animation demo on the captions demo page
      slideDelay: 400,
      plugins: [lgZoom, lgThumbnail],
      dynamicEl: $data,
    
      // Completely optional
      // Adding as the codepen preview is usually smaller
      thumbWidth: 100,
      thumbHeight: "60px",
      thumbMargin: 4
    });
    
    setTimeout(() => {
        inlineGallery.openGallery();
      }, 200);
}
}


