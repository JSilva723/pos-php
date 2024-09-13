document.addEventListener("DOMContentLoaded", function () {
    const priceInputs = document.querySelectorAll('.input-float');

    priceInputs.forEach(function (input) {
        const form = input.form

        form.addEventListener("submit", function (event) {
            let isValid = false;
            value = input.value.replace(",", ".");

            if (!isNaN(value) && value.trim() !== "") {
                input.value = parseFloat(value).toFixed(2);
                isValid = true;
            }
            
            if (!isValid) {
                event.preventDefault();
                input.classList.remove("border-gray-300", "dark:border-gray-600", "gray");
                input.classList.add("border-red-600");
            }
        })
    })
});