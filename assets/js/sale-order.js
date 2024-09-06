const inputId = document.getElementById('input-product-id')
const inputPrice = document.getElementById('input-product-price')
const inputQuantity = document.getElementById('input-product-quantity')
const inputSearch = document.getElementById('input-group-search')
const productsLi = document.querySelectorAll('.psearch')
const searchList = document.querySelector('.slist')
const searchListContainer = document.querySelector('.slistc')

let products = []

// Get products data
document.addEventListener('DOMContentLoaded', function () {
    productsLi.forEach(li => {
        products.push({
            name: li.innerText.trim(),
            id: li.dataset.id,
            price: li.dataset.price,
            quantity: 1,
        })
    })
})

document.addProduct = function (li) {
    inputId.value = li.dataset.id
    inputPrice.value = li.dataset.price
    inputQuantity.value = 1
    inputSearch.value = li.innerText
    searchListContainer.toggleAttribute('hidden')
    searchList.innerHTML = products.map(p => `<li class="block cursor-pointer px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white psearch" onclick="addProduct(this)" data-id="${p.id}" data-price="${p.price}">${p.name}</li>`).join("")
}

inputSearch.addEventListener('keyup', () => {
    searchList.innerHTML = products.filter(product => {
        return product.name.toLowerCase().includes(inputSearch.value.trim().toLowerCase())
    }).map(p => `<li class="block cursor-pointer px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white psearch" onclick="addProduct(this)" data-id="${p.id}" data-price="${p.price}">${p.name}</li>`).join("")
})

inputSearch.addEventListener('focus', () => {
    searchListContainer.toggleAttribute('hidden')
})

