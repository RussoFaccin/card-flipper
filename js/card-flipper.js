jQuery(document).ready(function($){
      var frame = wp.media({
        title: 'Select or Upload Media Of Your Chosen Persuasion',
        button: {
          text: 'Use this media'
        },
        multiple: false  // Set to true to allow multiple files to be selected
      });

      var fldUrl = null;
      var imgThumb = null;

      frame.on('select', function(evt) {
        var attachment = frame.state().get('selection').first().toJSON().url;
        fldUrl.value = attachment;
        imgThumb.src = attachment;
      })

      var btnsUpload = document.querySelectorAll('.cardflipper-upload');
      btnsUpload.forEach(function(item, index) {
        item.addEventListener('click', function(evt) {
          evt.preventDefault();
          fldUrl = evt.target.previousElementSibling;
          imgThumb = evt.target.parentNode.querySelector('.card-thumb');
          frame.open();
        });
      });
    });