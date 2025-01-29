// ============================





document.addEventListener("DOMContentLoaded", function() {
    var inputFields = document.querySelectorAll('.input-group .form-control');
    
    inputFields.forEach(function(field) {
        field.addEventListener("focus", function() {
            var inputGroup = this.closest('.input-group');
            if (inputGroup) {
                inputGroup.classList.add('input-group-focused');
            }
        });

        field.addEventListener("blur", function() {
            var inputGroup = this.closest('.input-group');
            if (inputGroup) {
                inputGroup.classList.remove('input-group-focused');
            }
        });
    });
});





// ================================================