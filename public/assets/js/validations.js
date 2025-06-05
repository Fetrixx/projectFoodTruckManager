// validations.js
const validateReserva = () => {
    const errors = [];
    
    if (!selectedFoodTruck) errors.push('Selecciona un food truck');
    if (cart.length === 0) errors.push('Agrega al menos un producto');
    
    return errors;
};

const showErrors = (errors) => {
    const errorContainer = document.getElementById('error-container');
    errorContainer.innerHTML = errors.map(e => `<div>${e}</div>`).join('');
};