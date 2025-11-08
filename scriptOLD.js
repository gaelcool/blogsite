// Validation Box System for CbNoticias Registration
document.addEventListener('DOMContentLoaded', function() {
  // Validation patterns (keep existing regex from register.html)
  const patterns = {
    nombre: /^[A-ZÁÉÍÓÚÑ\s]+$/,
    correo: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
    usuario: /^[a-zA-Z0-9_]{3,20}$/,
    clave: /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/,
    telefono: /^[0-9]{10}$/
  };

  // Help messages
  const messages = {
    nombre: 'Solo letras mayúsculas y espacios',
    correo: 'usuario@cbtis03.edu',
    usuario: '3-20 letras guiones bajos',
    clave: 'Mínimo 6 caracteres con letras y números',
    telefono: '10 dígitos'
  };

  // Field validation state
  const validState = {
    nombre: false,
    correo: false,
    usuario: false,
    clave: false,
    telefono: false
  };

  // Get  elements
  const form = document.getElementById('registerForm');
  const submitBtn = document.getElementById('submitBtn');
  const fields = ['nombre', 'correo', 'usuario', 'clave', 'telefono'];


  function updateSubmitButton() {
    const allValid = Object.values(validState).every(Boolean); //what the heck
    submitBtn.disabled = !allValid;
  }

  // Show validation box with help text

//Eventually i wanna rewrite this so that its just the inputs border that changes color to indicate validity, simpler.

  function showHelpText(fieldId) {
    const msgElement = document.getElementById(fieldId + 'Msg');
    msgElement.textContent = messages[fieldId];
    msgElement.className = 'validation-box show neutral';
  }

  // Hide validation box
  function hideValidationBox(fieldId) {
    const msgElement = document.getElementById(fieldId + 'Msg');
    msgElement.className = 'validation-box';
  }

  // Validate field and update UI
  function validateField(fieldId) {
    const field = document.getElementById(fieldId);
    const msgElement = document.getElementById(fieldId + 'Msg');
    const value = field.value.trim();
    
    if (value === '') {
      hideValidationBox(fieldId);
      validState[fieldId] = false;
      field.className = '';
      updateSubmitButton();
      return false;
    }
    
    const isValid = patterns[fieldId].test(value);
    validState[fieldId] = isValid;
    
    if (isValid) {
      msgElement.textContent = ' ' + messages[fieldId];
      msgElement.className = 'validation-box show valid';
      field.className = 'success';
    } else {
      msgElement.textContent = ' ' + messages[fieldId];
      msgElement.className = 'validation-box show invalid';
      field.className = 'error';
    }
    
    updateSubmitButton();
    return isValid;
  }


  // Set up event listeners for each field
  fields.forEach(fieldId => {
    const field = document.getElementById(fieldId);
    
    // Focus event - show help text
    field.addEventListener('focus', function() {
      showHelpText(fieldId);
    });
    
    // Blur event - hide if empty, otherwise validate
    field.addEventListener('blur', function() {
      if (this.value.trim() === '') {
        hideValidationBox(fieldId);
        validState[fieldId] = false;
        updateSubmitButton();
      } else {
        validateField(fieldId);
      }
    });
    
    // Input event - real-time validation
    field.addEventListener('input', function() {
      // Special handling for telefono (numbers only)
      if (fieldId === 'telefono') {
        this.value = this.value.replace(/\D/g, '');
      }
      
      validateField(fieldId);
    });
  });

  // Form submission
  form.addEventListener('submit', function(e) {
    const allValid = Object.values(validState).every(Boolean);
    
    if (!allValid) {
      e.preventDefault();
      alert('Por favor corrige los errores antes de continuar');
    }
  });

  // Initialize submit button as disabled
  updateSubmitButton();
});
