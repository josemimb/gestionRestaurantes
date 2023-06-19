$(document).ready(function() {

    $('#formulario').submit(function(e) {
      e.preventDefault();
  
      var valid = true;
      $('.error-message').empty();
  
      valid = valid && checkLength($('#nombre'), "nombre", 4, 50);
      valid = valid && checkLength($('#email'), "email", 10, 50);
      valid = valid && checkLength($('#nombre_restaurante'), "nombre_restaurante", 4, 50);
      valid = valid && checkLength($('#contacto'), "contacto", 6, 11);
      valid = valid && checkLength($('#mensaje'), "mensaje", 6, 500);
  
      valid = valid && checkRegexp($('#nombre'), /^[a-zA-Z\s]+$/, "El nombre solo puede contener letras y espacios.");
      valid = valid && checkRegexp($('#email'), /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/, "Por favor, introduce una dirección de correo electrónico válida.");
  
      if (valid) {
        $('#formulario')[0].submit();
      } else {
        showEmptyFieldErrors();
        scrollToError();
      }
    });

    $('#formulario_registro').submit(function(e) {
        e.preventDefault();
    
        var valid = true;
        $('.error-message').empty();
    
        valid = valid && checkLength($('#nombre'), "nombre", 4, 50);
        // valid = valid && checkLength($('#email'), "email", 10, 50);
        // valid = valid && checkLength($('#nombre_restaurante'), "nombre_restaurante", 4, 50);
        // valid = valid && checkLength($('#contacto'), "contacto", 6, 11);
        // valid = valid && checkLength($('#mensaje'), "mensaje", 6, 500);
    
        // valid = valid && checkRegexp($('#nombre'), /^[a-zA-Z\s]+$/, "El nombre solo puede contener letras y espacios.");
        // valid = valid && checkRegexp($('#email'), /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/, "Por favor, introduce una dirección de correo electrónico válida.");
    
        if (valid) {
          $('#formulario')[0].submit();
        } else {
          showEmptyFieldErrors();
          scrollToError();
        }
      });
  
    function checkEmpty($field, fieldName) {
      var value = $field.val().trim();
      if (value === '') {
        var errorDiv = $field.siblings('.error-message');
        errorDiv.html("El campo " + fieldName + " es obligatorio.");
        return false;
      }
      return true;
    }
  
    function checkLength(input, fieldName, min, max) {
      var inputValue = input.val();
      if (inputValue.length > max || inputValue.length < min) {
        var errorDiv = input.siblings('.error-message');
        errorDiv.html("El tamaño de " + fieldName + " debe de ser entre " + min + " y " + max + ".");
        return false;
      }
      return true;
    }
  
    function checkRegexp(input, regexp, errorMessage) {
      var inputValue = input.val();
      if (!regexp.test(inputValue)) {
        var errorDiv = input.siblings('.error-message');
        errorDiv.html(errorMessage);
        return false;
      }
      return true;
    }
  
    function showEmptyFieldErrors() {
      $('input').each(function() {
        var $field = $(this);
        var value = $field.val().trim();
        if (value === '') {
          var fieldName = $field.attr('name');
          var errorDiv = $field.siblings('.error-message');
          errorDiv.html("El campo " + fieldName + " es obligatorio.");
        }
      });
    }
  
    function scrollToError() {
      var errorElement = $('.error-message').first();
      if (errorElement.length) {
        $('html, body').animate({
          scrollTop: errorElement.offset().top - 100
        }, 500);
      }
    }



  });