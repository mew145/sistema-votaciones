// Funciones para manejar opciones en la creación de votaciones simples
function addOpcion() {
    const container = document.getElementById('opciones-container');
    const div = document.createElement('div');
    div.className = 'opcion-group';
    
    const input = document.createElement('input');
    input.type = 'text';
    input.name = 'opciones[]';
    input.required = true;
    
    const button = document.createElement('button');
    button.type = 'button';
    button.className = 'btn btn-remove';
    button.textContent = '-';
    button.onclick = function() { removeOpcion(this); };
    
    div.appendChild(input);
    div.appendChild(button);
    container.appendChild(div);
}

function removeOpcion(button) {
    const opcionGroups = document.querySelectorAll('.opcion-group');
    if (opcionGroups.length > 2) {
        button.parentElement.remove();
    } else {
        alert('Debe haber al menos 2 opciones.');
    }
}

// Validación de fechas en formularios
document.addEventListener('DOMContentLoaded', function() {
    const forms = document.querySelectorAll('form');
    
    forms.forEach(form => {
        if (form.querySelector('[type="datetime-local"]')) {
            form.addEventListener('submit', function(e) {
                const fechaInicio = form.querySelector('[name="fecha_inicio"]');
                const fechaFin = form.querySelector('[name="fecha_fin"]');
                
                if (fechaInicio && fechaFin) {
                    const inicio = new Date(fechaInicio.value);
                    const fin = new Date(fechaFin.value);
                    
                    if (inicio >= fin) {
                        e.preventDefault();
                        alert('La fecha de inicio debe ser anterior a la fecha de fin.');
                    }
                }
            });
        }
    });
});

// Mostrar/ocultar contraseña
function togglePassword(inputId) {
    const input = document.getElementById(inputId);
    if (input.type === 'password') {
        input.type = 'text';
    } else {
        input.type = 'password';
    }
}