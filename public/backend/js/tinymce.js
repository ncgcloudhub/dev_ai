$(function() {
  'use strict';

  //Tinymce editor
  if ($("#tinymceExample").length) {
    tinymce.init({
      selector: '#tinymceExample',
      min_height: 350,
      default_text_color: 'red',
      plugins: [
        'advlist', 'autoresize', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview', 'anchor', 'pagebreak',
        'searchreplace', 'wordcount', 'visualblocks', 'visualchars', 'code', 'fullscreen',
      ],
      toolbar1: 'undo redo | insert | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
      toolbar2: 'print preview media | forecolor backcolor emoticons | codesample help',
      image_advtab: true,
      templates: [{
          title: 'Test template 1',
          content: 'Test 1'
        },
        {
          title: 'Test template 2',
          content: 'Test 2'
        }
      ],
      content_css: [],
      setup: function(editor) {
        editor.on('BeforeSetContent', function(e) {
          // Unescape HTML entities before rendering
          e.content = tinymce.html.Entities.decode(e.content);
        });
        editor.on('SaveContent', function(e) {
          // Escape HTML entities before saving
          e.content = tinymce.html.Entities.encodeAllRaw(e.content);
        });
      }
    });
  }
});
