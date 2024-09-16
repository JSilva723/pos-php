const inputId = document.getElementById('input-product-id')
const inputPrice = document.getElementById('input-product-price')
const labelQuantity = document.querySelector('label[for="quantity"]')
const inputQuantity = document.getElementById('input-product-quantity')
const inputSearch = document.getElementById('input-group-search')
const productsLi = document.querySelectorAll('.psearch')
const searchList = document.querySelector('.slist')
const searchListContainer = document.querySelector('.slistc')

let products = []

function getQuantityLabel(textContent, uom){
    const words = textContent.split(' ')
    return `${words[0]} (${uom})`
}

function getListItem(p){
    return `<li class="block cursor-pointer px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white psearch" onclick="addProduct(this)" data-id="${p.id}" data-price="${p.price}" data-uom="${p.uom}">${p.name}</li>`
}

// Get products data
document.addEventListener('DOMContentLoaded', function () {
    productsLi.forEach(li => {
        products.push({
            name: li.innerText.trim(),
            id: li.dataset.id,
            price: li.dataset.price,
            uom: li.dataset.uom,
            quantity: 1,
        })
    })
})

document.addProduct = function (li) {
    inputId.value = li.dataset.id
    inputPrice.value = li.dataset.price
    labelQuantity.textContent = getQuantityLabel(labelQuantity.textContent, li.dataset.uom)
    inputQuantity.value = 1
    inputSearch.value = li.innerText
    searchListContainer.toggleAttribute('hidden')
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

