const inputId = document.getElementById('input-product-id')
const inputPrice = document.getElementById('input-product-price')
const labelQuantity = document.querySelector('label[for="quantity"]')
const inputQuantity = document.getElementById('input-product-quantity')
const inputSearch = document.getElementById('input-group-search')
const productsLi = document.querySelectorAll('.psearch')
const searchList = document.querySelector('.slist')
const searchListContainer = document.querySelector('.slistc')
const uomContainer = document.querySelector('.uom-container')
const uomSelect = document.querySelector('.uom-select')

let products = []

function getQuantityLabel(textContent, uom, uomfs){
    const words = textContent.split(' ')
    return `${words[0]} (${uomfs ? uomfs : uom})`
}

function getListItem(p){
    return `<li class="block cursor-pointer px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white psearch" onclick="addProduct(this)" data-id="${p.id}" data-price="${p.price}" data-uom="${p.uom}" data-uomfs="${p.uomfs}">${p.name}</li>`
}

function createOption(opt){
    const option = document.createElement('option')
    option.setAttribute('value', opt)
    option.innerText = opt

    return option
}

document.addEventListener('DOMContentLoaded', function () {
    inputId.value = null
    inputPrice.value = null
    inputQuantity.value = null
    inputSearch.value = null
    productsLi.forEach(li => {
        products.push({
            name: li.innerText.trim(),
            id: li.dataset.id,
            price: li.dataset.price,
            uom: li.dataset.uom,
            uomfs: li.dataset.uomfs,
            quantity: 1,
        })
    })
})

document.addProduct = function (li) {
    inputId.value = li.dataset.id
    inputPrice.value = li.dataset.price
    labelQuantity.textContent = getQuantityLabel(labelQuantity.textContent, li.dataset.uom, li.dataset.uomfs)
    inputQuantity.value = 1
    inputSearch.value = li.innerText
    searchListContainer.toggleAttribute('hidden')

    if(li.dataset.uomfs){
        uomContainer.removeAttribute('hidden')
        const optionUOM = createOption(li.dataset.uom)
        const optionUOMFS = createOption(li.dataset.uomfs)
        uomSelect.appendChild(optionUOMFS)
        uomSelect.appendChild(optionUOM)
    }else{
        uomContainer.setAttribute('hidden', 'true')
    }

    searchList.innerHTML = products.map(getListItem).join("")
}

inputSearch.addEventListener('keyup', () => {
    searchList.innerHTML = products.filter(product => {
        return product.name.toLowerCase().includes(inputSearch.value.trim().toLowerCase())
    }).map(getListItem).join("")
})

inputSearch.addEventListener('focus', () => {
    searchListContainer.toggleAttribute('hidden')
})