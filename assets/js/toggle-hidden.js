const toHidden = document.querySelector('.to-hidden')

const checkInput = document.querySelector('input[type="checkbox"]')

checkInput.addEventListener('change', () => {
    toHidden.toggleAttribute('hidden')
})