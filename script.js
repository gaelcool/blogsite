// Validation Box System for CbNoticias Registration
document.addEventListener('DOMContentLoaded', function() {
  // Validation patterns
  const patterns = {
    nombre: /^[a-záéíóúñA-ZÁÉÍÓÚÑ\s]+$/i,
    correo: /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/,
    usuario: /^[a-zA-Z0-9_]{3,20}$/,
    clave: /^.{6,}$/,
    telefono: /^[0-9]{10}$/
  };

  // Help messages (only for text fields)
  const messages = {
    nombre: 'Solo letras y espacios',
    correo: 'usuario@cbtis03.edu',
    usuario: '3-20 caracteres (letras, números, guiones bajos)',
    clave: 'Mínimo 6 caracteres',
    telefono: '10 dígitos (opcional)'
  };

  // Field validation state
  const validState = {
    nombre: false,
    correo: false,
    usuario: false,
    clave: false,
    telefono: true 
  };

  // Get elements
  const form = document.getElementById('registerForm');
  const submitBtn = document.getElementById('submitBtn');
  
  // Only text input fields that need validation
  const textFields = ['nombre', 'correo', 'usuario', 'clave', 'telefono'];

  /**
   * Update submit button state
   */
  function updateSubmitButton() {
    const allValid = Object.values(validState).every(Boolean);
    submitBtn.disabled = !allValid;
  }

  /**
   * Show help text in validation box (only for text fields)
   */
  function showHelpText(fieldId) {
    const msgElement = document.getElementById(fieldId + 'Msg');
    if (msgElement && messages[fieldId]) {
      msgElement.textContent = messages[fieldId];
      msgElement.className = 'validation-box show neutral';
    }
  }

  /**
   * Hide validation box
   */
  function hideValidationBox(fieldId) {
    const msgElement = document.getElementById(fieldId + 'Msg');
    if (msgElement) {
      msgElement.className = 'validation-box';
      msgElement.textContent = '';
    }
  }

  /**
   * Validate text field and update UI
   */
  function validateField(fieldId) {
    const field = document.getElementById(fieldId);
    if (!field) return false;
    
    const msgElement = document.getElementById(fieldId + 'Msg');
    const value = field.value.trim();
    
    // Empty field handling
    if (value === '') {
      // Telefono is optional
      if (fieldId === 'telefono') {
        hideValidationBox(fieldId);
        validState[fieldId] = true;
        field.className = '';
        updateSubmitButton();
        return true;
      } else {
        hideValidationBox(fieldId);
        validState[fieldId] = false;
        field.className = '';
        updateSubmitButton();
        return false;
      }
    }
    
    // Validate against pattern
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

  /**
   * Set up event listeners for text fields
   */
  textFields.forEach(fieldId => {
    const field = document.getElementById(fieldId);
    if (!field) return;
    
    // Focus event - show help text
    field.addEventListener('focus', function() {
      showHelpText(fieldId);
    });
    
    // Blur event - hide if empty, otherwise validate
    field.addEventListener('blur', function() {
      if (this.value.trim() === '' && fieldId !== 'telefono') {
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
        this.value = this.value.replace(/\D/g, '').substring(0, 10);
      }
      
      validateField(fieldId);
    });
  });

  /**
   * Form submission validation
   */
  form.addEventListener('submit', function(e) {
    // Validate all text fields one more time
    let allValid = true;
    
    // textFields.forEach(fieldId => {
    //   if (!validateField(fieldId) && fieldId !== 'telefono') {
    //     allValid = false;
    //   }
    // });
    
    if (!allValid) {
      e.preventDefault();
      alert('Por favor completa todos los campos correctamente.');
      return false;
    }
  });

  // Initialize submit button as disabled
  updateSubmitButton();
});